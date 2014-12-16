package ru.dreamkas.pos.view.popup;

import android.app.Activity;
import android.app.Dialog;
import android.app.DialogFragment;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.view.WindowManager;
import android.view.inputmethod.InputMethodManager;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.Toast;

import com.gc.materialdesign.views.ButtonFlat;

import ru.dreamkas.pos.controller.requests.AuthRequest;
import ru.dreamkas.pos.view.components.regular.ButtonFlatExt;
import ru.dreamkas.pos.view.components.regular.ButtonRectangleExt;

import com.octo.android.robospice.SpiceManager;
import com.octo.android.robospice.exception.RequestCancelledException;
import com.octo.android.robospice.persistence.DurationInMillis;
import com.octo.android.robospice.persistence.exception.SpiceException;

import org.androidannotations.annotations.AfterViews;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EFragment;
import org.androidannotations.annotations.ViewById;
import org.springframework.http.HttpStatus;
import org.springframework.util.StringUtils;
import org.springframework.web.client.HttpClientErrorException;

import ru.dreamkas.pos.DreamkasApp;
import ru.dreamkas.pos.R;
import ru.dreamkas.pos.controller.listeners.request.AuthRequestListener;
import ru.dreamkas.pos.controller.listeners.request.IAuthRequestHandler;
import ru.dreamkas.pos.controller.requests.AuthRequest_;
import ru.dreamkas.pos.model.api.AuthObject;
import ru.dreamkas.pos.model.api.Token;

@EFragment(R.layout.login_dialog)
public class LoginDialog extends RequestContainingDialog implements IAuthRequestHandler {

    @ViewById
    ImageButton btnCloseModal;

    @ViewById
    ButtonRectangleExt btnLogin;

    @ViewById
    EditText txtUsername;

    @ViewById
    EditText txtPassword;

    @ViewById
    ButtonFlatExt btnRestorePassword;

    public AuthRequestListener authRequestListener = new AuthRequestListener(this);

    private Token mAuthResult;

    public LoginDialog() {
        super();
    }

    @Override
    public void onStart(){
        super.onStart();

        txtUsername.addTextChangedListener(mValidateTextWatcher);
        txtPassword.addTextChangedListener(mValidateTextWatcher);

        validate(false);
    }

    public String getAccessToken() {
        return mAuthResult.getAccess_token();
    }

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

    @Click(R.id.btnRestorePassword)
    void resotePassword(){
        txtUsername.setText("androidpos@lighthouse.pro");
        txtPassword.setText("lighthouse");
    }

    @Click(R.id.btnLogin)
    public void login(){
        InputMethodManager imm = (InputMethodManager)getActivity().getSystemService(Context.INPUT_METHOD_SERVICE);
        imm.hideSoftInputFromWindow(txtPassword.getWindowToken(), 0);

        showProgressDialog(getResources().getString(R.string.auth_dialog_title));
        String username = txtUsername.getText().toString();
        String password = txtPassword.getText().toString();
        //todo get secret from? get client_id from?
        AuthObject ao = new AuthObject("webfront_webfront", username, password, "secret");
        AuthRequest authRequest = AuthRequest_.getInstance_(getActivity());
        authRequest.setCredentials(ao);
        getSpiceManager().execute(authRequest, null, DurationInMillis.NEVER, authRequestListener);
        authRequestListener.requestStarted();
    }

    @Override
    @Click(R.id.btnCloseModal)
    public void cancel(){
        super.cancel();
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
        stopProgressDialog();

        setResult(DialogResult.OK);
        mAuthResult = authResult;
        dismiss();
    }

    @Override
    public void onAuthFailureRequest(SpiceException spiceException){
        stopProgressDialog();

        String msg = "";
        if(spiceException.getCause() instanceof HttpClientErrorException){
            HttpClientErrorException exception = (HttpClientErrorException)spiceException.getCause();
            if(exception.getStatusCode().equals(HttpStatus.BAD_REQUEST)){
                //wrong credentials
                msg = getActivity().getResources().getString(R.string.error_bad_credentials);
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

        Toast.makeText(getActivity(), msg, Toast.LENGTH_LONG).show();
    }
}

