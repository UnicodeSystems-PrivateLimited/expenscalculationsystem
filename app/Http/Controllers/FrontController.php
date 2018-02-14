<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;
use App\Models\Users;
use App\Models\ContactUs;
use App\Models\UsersGroups;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Validator;
use App\Models\ApiAccounts;
use Illuminate\Foundation\helpers;
use LaravelAcl\Authentication\Validators\ReminderValidator;
use LaravelAcl\Authentication\Services\ReminderService;
use LaravelAcl\Authentication\Interfaces\AuthenticateInterface;
use LaravelAcl\Authentication\Validators\UserValidator;
use LaravelAcl\Library\Exceptions\NotFoundException;
use LaravelAcl\Authentication\Exceptions\AuthenticationErrorException;
use LaravelAcl\Authentication\Exceptions\PermissionException;
use Illuminate\Support\Facades\Hash;
use File;
use View,
    URL,
    Redirect,
    App,
    DB,
    Config,
    Auth;

class FrontController extends Controller {

    protected $authenticator;
//    private static $to = 'david@globalvatax.com';
    private static $to = 'pritesh.singh@unicodesystems.org';
    private static $cc = 'mamta@unicodesystems.in';
    private static $fromEmail = 'mamta@unicodesystems.in';
    private static $fromName = 'Vatax Cloud';

    const EXCEL_FILE_UPLOAD_PATH = 'app/public/excel-file';

    public function __construct() {
        $this->authenticator = App::make('authenticator');
    }

    public function login() {
        $authentication = App::make('authenticator');
        $user = $authentication->getLoggedUser();
        if ($user && $user->id) {
            return redirect()->route('dashboard');
        } else {
            return view('user.login');
        }
    }

    public function signup() {
        return view('user.signup');
    }

    public function postSignup(Request $request) {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'company_name' => 'required',
            'phone' => 'digits_between:8,12',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:password_confirmation',
            'password_confirmation' => 'required'
                ], [
            'same' => 'The password and confirm password do not match.',
            'phone.digits_between' => 'The phone number must be between 8 and 12 digits.'
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($data['password']); //Generating encrypted password
        $user = Users::create(self::createUserInput($data));
        UserProfile::create(self::createProfileInput($data, $user));
        UsersGroups::createUsersGroups(['user_id' => $user->id, 'group_id' => 4]);
        self::sendRegistrationMails($data);

        return Redirect::route("account.signup-success");
    }

    private function createUserInput(array $input) {
        return array_except($input, ["_token", "__to_hide_password_autocomplete", "first_name", "last_name", "company_name", "phone", "password_confirmation"]);
    }

    private function createProfileInput(array $input, $user) {
        return array_merge(["user_id" => $user->id], array_except($input, ["_token", "__to_hide_password_autocomplete", "email", "password", "password_confirmation", "is_owner"]));
    }

    private function sendRegistrationMails(array $data) {
        $mailClient = Mail::send('emails.registration-client', $data, function ($message) use ($data) {
                    $message->from('no-reply@vataxcloud.com', 'Vatax Cloud');
                    $message->to($data['email'])->subject('Registered Successfully!!!');
                });

        $mailAdmin = Mail::send('emails.registration-admin', $data, function ($message) use ($data) {
                    $message->from('no-reply@vataxcloud.com', 'Vatax Cloud');
                    $message->to(self::$to)->cc(self::$cc)->subject('User Registration request!!!');
                });
    }

    public function postLogin(Request $request) {
        list($email, $password, $remember) = $this->getLoginInput($request);
        try {
            $this->authenticator->authenticate(array(
                "email" => $email,
                "password" => $password
                    ), $remember);
            $user = session()->put('email', $email);
            return redirect()->route('dashboard');
        } catch (NotFoundException $e) {
            $errors = $this->authenticator->getErrors();
            return redirect()->route("login")->withInput()->withErrors($errors);
        } catch (AuthenticationErrorException $e) {
            $errors = $this->authenticator->getErrors();
            return redirect()->route("login")->withInput()->withErrors($errors);
        }
        return $user;
    }

    /**
     * @return array
     */
    private function getLoginInput(Request $request) {
        $email = $request->get('email');
        $password = $request->get('password');
        $remember = $request->get('remember');
        return array($email, $password, $remember);
    }

    public function Logout() {
        $this->authenticator->logout();
        session()->flush();
        return redirect()->route('login');
    }

    public function forgotPassword() {
        return view("user.forgot");
    }

    public function getforgotPassword(Request $request) {
        try {
            if (empty($request->email)) {
                throw new NotFoundException('Email Required');
            }
            $count = Users::where('email', $request->email)->count();
            if ($count) {
                $link = URL::route('user.forget-password');
                $token = csrf_token();
                $link = $link . '?token=' . $token;
                $data = ['name' => 'User', 'email' => $request->email, 'link' => $link];
                $mail = Mail::send('emails.forgotPassword', $data, function ($message) use ($data) {
                            $message->from(self::$fromEmail, self::$fromName);
                            $message->to($data['email'])->subject('Forgot Your Password');
                        });
                DB::table('users')->where('email', $request->email)->update(array('reset_password_code' => $token));
                return redirect()->route("account.reminder-success");
            } else {
                $errors = 'There is no user associated with this email.';
                return redirect()->route("recovery-password")->withErrors($errors);
            }
        } catch (NotFoundException $e) {
            $errors = $this->reminder->getErrors();
            return redirect()->route("recovery-password")->withErrors($errors);
        }
    }

    public function resetPassword() {
        return view("user.reset-password");
    }

    public function resetForgetPassword() {
        return view("user.change-forget-password");
    }

    public function checkForgetPassword(Request $request) {
        try {
            if (empty($request->password)) {
                throw new NotFoundException('Password Required');
            }
            if (empty($request->_token)) {
                throw new NotFoundException('Token Required');
            }

            $this->validate($request, [
                'password' => 'required|same:password_confirmation',
                'password_confirmation' => 'required'
            ]);
            $password = $request->password;
            $pwd = bcrypt($password);
            $_token = $request->_token;
            $result = DB::table('users')->where('reset_password_code', $_token)->update(array('password' => $pwd, 'reset_password_code' => NULL));

            return redirect()->route("account.password-reset-success")->withInput()->withErrors('testing');
        } catch (NotFoundException $e) {
            $errors = $this->authenticator->getErrors();
            //return redirect()->route("account.reset-password")->withInput()->withErrors($errors);
		return redirect()->back()->withInput()->withErrors($errors);
        }
    }

    public function checkPassword(Request $request) {
        try {
            if (empty($request->password)) {
                throw new NotFoundException('Password Required');
            }

            $this->validate($request, [
                'old_password' => 'required',
                'password' => 'required|same:password_confirmation',
                'password_confirmation' => 'required'
            ]);
            $id = array_values(session()->get('laravel_acl_sentry'))[0];
            $user = Users::find($id);
            $password = $request->input('password');
            $pwd = bcrypt($password);
            $oldPwd = $request->input('old_password');

            if (Hash::check($oldPwd, $user->password)) {
                DB::table('users')->where('id', $id)->update(array('password' => $pwd, 'reset_password_code' => NULL));
                return redirect()->route("account.password-reset-success")->withInput()->withErrors('testing');
            } else {
                $errors = 'Old password does not Match';
                return redirect()->back()->withInput()->withErrors($errors);
            }
        } catch (NotFoundException $e) {
            $errors = $this->authenticator->getErrors();
            return redirect()->route("user.reset-password")->withInput()->withErrors($errors);
        }
    }

    public function dashboard() {
        $id = array_values(session()->get('laravel_acl_sentry'))[0];
        $userFirstName = json_decode(Users::getUserDetails($id), true);
        $type = ApiAccounts::checkUserType($id);
        $role = Users::getUserRole($id);
        session()->put('first_name', $userFirstName[0]['first_name']);
        session()->put('company_name', $userFirstName[0]['company_name']);
        session()->put('group', $role[0]->group_id);
        session()->put('loggedin', TRUE);
        if (empty($type)) {
            session()->put('type', null);
            return View::make('dashboard')->with(['type' => null, 'group' => $role[0]->group_id]);
        } else {
            session()->put('type', $type[0]['type']);
            return View::make('dashboard')->with(['type' => $type[0]['type'], 'group' => $role[0]->group_id]);
        }
    }

    public function showUserDetails() {

        $clientList = Users::getClientList();
        return View::make('user-list')->with(['clientList' => $clientList]);
    }

    public function searchUserDetails(Request $request) {

        $clientList = Users::searchClientList($request->code);
        session()->flash('code', $request->code);
        return View::make('user-list')->with(['clientList' => $clientList]);
    }

    public function showUserprofile() {

        $id = array_values(session()->get('laravel_acl_sentry'))[0];
        $clientProfile = Users::getUserDetails($id);
        return View::make('user-profile')->with(['clientProfile' => $clientProfile]);
    }

    public function editProfile(Request $request) {

        $data = $request->only('first_name', 'last_name', 'phone', 'company_name', 'address');
        $id = array_values(session()->get('laravel_acl_sentry'))[0];
        UserProfile::updateUserProfile($data, $id);

        return redirect()->back();
    }

    public function sendEmail(Request $request) {
        $data = [];
        if ($request->isMethod('post')) {

            $file = $request->only('excel_file');
            $count = count($file['excel_file']);
            if ($count > 0) {
                for ($i = 0; $i < $count; $i++) {
                    $extension[] = File::extension($file['excel_file'][$i]->getClientOriginalName());
                }
                $data = array('doc','docx', 'pdf', 'xls', 'xlsx', 'csv');
                $diff = array_diff($extension, $data);

                if (empty($diff)) {

                    Mail::to(self::$to)->cc(self::$cc)->send(new class($request) extends Mailable {

                        private $request;

                        public function __construct(Request $request) {
                            $this->request = $request;
                        }

                        public function build() {
                            $id = array_values(session()->get('laravel_acl_sentry'))[0];
                            $user = Users::getUserDetails($id);
                            $count = count($this->request->excel_file);

                            $data = $this->view('emails.excel-mail')
                                    ->subject('Expense Excel File [' . $user[0]->company_name . ']')
                                    ->from($user[0]->email, $user[0]->first_name);

                            for ($j = 0; $j < $count; $j++) {
                                $path = $this->request->excel_file[$j]->path();
                                $attachment = $this->request->excel_file[$j]->getClientOriginalName();
                                $data->attach($path, ['as' => $attachment]);
                            }
                            return $data;
                        }
                    });
                    $data['message'] = 'Files sent successfully';
                } else {
                    $errors = 'Please upload a valid xlsx/xls/csv/doc/docx/pdf file.';
                    return redirect()->back()->withInput()->withErrors($errors);
                }
            } else {
                $errors = 'Please select atleast one file.';
                return redirect()->back()->withInput()->withErrors($errors);
            }
        }
        return view('send-email', $data);
    }

    public function contactUs(Request $request) {
        $data = [];
        $data['name'] = '';
        $data['email'] = '';
        $data['phone'] = '';
        $data['company_name'] = '';
        if (session()->get('loggedin')) {
            $id = array_values(session()->get('laravel_acl_sentry'))[0];
            $user = Users::getUserDetails($id);
            $data['name'] = $user[0]->first_name . ' ' . $user[0]->last_name;
            $data['email'] = $user[0]->email;
            $data['phone'] = $user[0]->phone;
            $data['company_name'] = $user[0]->company_name;
        }
        $message = $request->only('name', 'from_email', 'subject', 'message', 'company_name', 'phone_number');
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'name' => 'required',
                'from_email' => 'required|email',
                'company_name' => 'required',
                'phone_number' => 'digits_between:8,12',
                'subject' => 'required',
                'message' => 'required']);
            //print_r($message); exit;  
            ContactUs::saveMessage($message);
            Mail::to(self::$to)->cc(self::$cc)->send(new class($request) extends Mailable {

                private $request;

                public function __construct(Request $request) {
                    $this->request = $request;
                }

                public function build() {
                    return $this->view('emails.contactUs-mail')
                                    ->subject($this->request->subject)
                                    ->from($this->request->from_email)
                                    ->with(['emailMessage' => $this->request->message]);
                }
            });
            $data['message'] = 'Mail sent successfully';
        }
        return view('contactUs', $data);
    }

    public function addExcelValidationRule() {
        Validator::extend('excel', function ($attribute, $value) {
            return in_array($value->extension(), ['xls', 'xlsx']);
        });
    }

    public function statusChange(Request $request) {
        $data = ['activated' => !$request->status];
        Users::where('id', $request->id)->update($data);
        return redirect()->route("user-details");
    }

    public function associateEmail(Request $request) {
        $emails = ApiAccounts::getUserIdAndEmail();
        return View::make('associate-email')->with(['emails' => $emails, 'user_id' => $request->id]);
    }

    public function associateEmailWithUser(Request $request) {
        $data = ['id' => $request->associated_email, 'user_id' => $request->user_id, 'type' => 3];
        ApiAccounts::associateEmailWithUser($data);
        return redirect()->route("user-details");
    }

    public function contactRequestList() {
        $contactRequestList = ContactUs::getContactRequestList();
        return View::make('contact-request-list')->with(['contactRequestList' => $contactRequestList]);
    }

    public function getContactRequestDetails(Request $request) {
        $contactDetails = ContactUs::getContactDetailsById($request->id);
        return View::make('contact-request-details')->with(['contactDetails' => $contactDetails]);
    }

    public function searchContactRequestList(Request $request) {
        $contactRequestList = DB::table('contact_us')->where('name', 'LIKE', '%' . $request->code . '%')->orWhere('from_email', 'LIKE', '%' . $request->code . '%')->orWhere('phone_number', 'LIKE', '%' . $request->code . '%')->paginate(10)->setPath('');
        session()->flash('code', $request->code);
        return View::make('contact-request-list')->with(['contactRequestList' => $contactRequestList]);
    }

    public function deleteUser(Request $request) {
        Users::find($request->id)->delete();
        ApiAccounts::deleteApiAccount($request->id);
        return redirect()->route('user-details');
    }

}
