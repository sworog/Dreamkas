package ru.dreamkas.pos.view.popup;

import android.app.Activity;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.MotionEvent;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.view.inputmethod.InputMethodManager;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.Toast;

import com.gc.materialdesign.views.ButtonFlat;
import com.gc.materialdesign.views.ButtonRectangle;
import com.octo.android.robospice.SpiceManager;
import com.octo.android.robospice.exception.RequestCancelledException;
import com.octo.android.robospice.persistence.DurationInMillis;
import com.octo.android.robospice.persistence.exception.SpiceException;

import org.springframework.http.HttpStatus;
import org.springframework.util.StringUtils;
import org.springframework.web.client.HttpClientErrorException;

import ru.dreamkas.pos.DreamkasApp;
import ru.dreamkas.pos.R;
import ru.dreamkas.pos.controller.DreamkasSpiceService;
import ru.dreamkas.pos.controller.listeners.request.AuthRequestListener;
import ru.dreamkas.pos.controller.listeners.request.IAuthRequestHandler;
import ru.dreamkas.pos.controller.requests.AuthRequest_;
import ru.dreamkas.pos.model.api.AuthObject;
import ru.dreamkas.pos.model.api.Token;

public class LoginDialog extends Dialog implements IAuthRequestHandler {

    private final Activity mOwnerActivity;
    private DialogResult result = DialogResult.Cancel;
    private ImageButton btnClose;
    private ButtonRectangle btnLogin;
    private EditText txtUsername;
    private EditText txtPassword;

    private SpiceManager mSpiceManager;
    private AuthRequest_ authRequest;
    public ProgressDialog progressDialog;


    public AuthRequestListener authRequestListener = new AuthRequestListener(this);
    private Token mAuthResult;
    private ButtonFlat btnRestorePassword;

    public String getAccessToken() {
        return mAuthResult.getAccess_token();
    }

    public enum DialogResult{Login, Cancel;}

    private TextWatcher mValidateTextWatcher = new TextWatcher() {
        @Override
        public void beforeTextChanged(CharSequence s, int start, int count, int after) {

        }

        @Override
        public void onTextChanged(CharSequence s, int start, int before, int count) {

        }

        @Override
        public void afterTextChanged(Editable s) {
            validate(false);
        }
    };

    public LoginDialog(Activity context) {
        super(context, R.style.dialog_slide_anim);
        mOwnerActivity = context;
    }

    @Override
    public void show(){
        getWindow().setBackgroundDrawableResource(android.R.color.transparent);
        requestWindowFeature(Window.FEATURE_NO_TITLE);
        setContentView(R.layout.login_dialog);
        setCanceledOnTouchOutside(true);

        WindowManager.LayoutParams lp = new WindowManager.LayoutParams();
        lp.copyFrom(getWindow().getAttributes());
        lp.width = 800;
        lp.height = WindowManager.LayoutParams.MATCH_PARENT;

        super.show();

        getWindow().setAttributes(lp);

        this.init();

        startService();
    }

    @Override
    public boolean onTouchEvent(MotionEvent event) {
        return false;
    }

    @Override
    public boolean dispatchTouchEvent(MotionEvent ev) {
        return super.dispatchTouchEvent(ev);
    }

    @Override
    public void dismiss(){
        stopService();
        super.dismiss();
    }

    @Override
    public void cancel(){
        result = DialogResult.Cancel;
        stopService();
        super.cancel();
    }

    private void startService(){
        mSpiceManager = new SpiceManager(DreamkasSpiceService.class);
        mSpiceManager.start(mOwnerActivity);
        authRequest = AuthRequest_.getInstance_(getContext());
    }

    private void stopService() {
        if(mSpiceManager.isStarted()){
            mSpiceManager.shouldStop();
        }
    }

    private void init() {
        btnLogin = (ButtonRectangle) findViewById(R.id.btnLogin);
        btnLogin.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                login();
            }
        });

        btnClose = (ImageButton) findViewById(R.id.btnCloseModal);
        btnClose.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                cancel();
            }
        });

        btnRestorePassword = (ButtonFlat) findViewById(R.id.btnRestorePassword);
        btnRestorePassword.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                txtUsername.setText("androidpos@lighthouse.pro");
                txtPassword.setText("lighthouse");
            }
        });

        txtUsername = (EditText) findViewById(R.id.txtUsername);
        txtPassword = (EditText) findViewById(R.id.txtPassword);

        txtUsername.addTextChangedListener(mValidateTextWatcher);
        txtPassword.addTextChangedListener(mValidateTextWatcher);

        validate(false);
    }

    public void login(){
        InputMethodManager imm = (InputMethodManager)mOwnerActivity.getSystemService(Context.INPUT_METHOD_SERVICE);
        imm.hideSoftInputFromWindow(txtPassword.getWindowToken(), 0);

        showProgressDialog();
        String username = txtUsername.getText().toString();
        String password = txtPassword.getText().toString();
        //todo get secret from? get client_id from?
        AuthObject ao = new AuthObject("webfront_webfront", username, password, "secret");
        authRequest.setCredentials(ao);
        mSpiceManager.execute(authRequest, null, DurationInMillis.NEVER, authRequestListener);
        authRequestListener.requestStarted();
    }

    public DialogResult getResult(){
        return result;
    }

    private void validate(){
        validate(true);
    }

    private void validate(boolean showError){
        String username = txtUsername.getText().toString();
        String password = txtPassword.getText().toString();
        boolean result = true;

        if (!StringUtils.hasText(username)){
            result = false;
            if(showError){
                txtUsername.setError(DreamkasApp.getResourceString(R.string.error_empty_field));
            }
        }

        if (!StringUtils.hasText(password)){
            result = false;
            if(showError){
                txtPassword.setError(DreamkasApp.getResourceString(R.string.error_empty_field));
            }
        }

        if(result){
            btnLogin.setEnabled(true);
        }else {
            btnLogin.setEnabled(false);
        }
    }

    @Override
    public void onAuthSuccessRequest(Token authResult){
        progressDialog.dismiss();
        result = DialogResult.Login;
        mAuthResult = authResult;
        dismiss();
    }

    @Override
    public void onAuthFailureRequest(SpiceException spiceException){
        progressDialog.dismiss();

        String msg = "";
        if(spiceException.getCause() instanceof HttpClientErrorException){
            HttpClientErrorException exception = (HttpClientErrorException)spiceException.getCause();
            if(exception.getStatusCode().equals(HttpStatus.BAD_REQUEST)){
                //wrong credentials
                msg = getContext().getResources().getString(R.string.error_bad_credentials);
            }
            else{
                //other Network exception
                msg = spiceException.getMessage();
            }
        }
        else if(spiceException instanceof RequestCancelledException){
            //cancelled
            msg = spiceException.getMessage();
        }
        else{
            //other exception
            msg = spiceException.getMessage();
        }

        Toast.makeText(getContext(), msg, Toast.LENGTH_LONG).show();
    }

    protected void showProgressDialog(){
        progressDialog = new ProgressDialog(getContext());
        progressDialog.setMessage(getContext().getResources().getString(R.string.auth_dialog_title));
        progressDialog.setIndeterminate(true);
        progressDialog.setCancelable(true);
        progressDialog.show();
    }
}