package ru.crystals.vaverjanov.dreamkas.view;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.inputmethod.InputMethodManager;
import android.widget.EditText;
import android.widget.Toast;

import com.octo.android.robospice.SpiceManager;
import com.octo.android.robospice.exception.RequestCancelledException;
import com.octo.android.robospice.persistence.DurationInMillis;
import com.octo.android.robospice.persistence.exception.SpiceException;

import org.androidannotations.annotations.Bean;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EActivity;
import org.androidannotations.annotations.ViewById;
import org.springframework.http.HttpStatus;
import org.springframework.util.StringUtils;
import org.springframework.web.client.HttpClientErrorException;

import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.controller.AuthRequest;
import ru.crystals.vaverjanov.dreamkas.controller.LighthouseSpiceService;
import ru.crystals.vaverjanov.dreamkas.controller.PreferencesManager;
import ru.crystals.vaverjanov.dreamkas.controller.listeners.IAuthRequestHandler;
import ru.crystals.vaverjanov.dreamkas.controller.listeners.AuthRequestListener;
import ru.crystals.vaverjanov.dreamkas.model.AuthObject;
import ru.crystals.vaverjanov.dreamkas.model.Token;

@EActivity(R.layout.activity_login)
public class LoginActivity extends Activity implements IAuthRequestHandler
{
    public boolean isActive = false;

    @ViewById
    EditText txtPassword;

    @ViewById
    EditText txtUsername;

    private Context context;

    @Bean
    public AuthRequest authRequest;

    private SpiceManager spiceManager = new SpiceManager(LighthouseSpiceService.class);
    public AuthRequestListener authRequestListener;// = new AuthRequestListener(this);
    //public final AuthRequestListener authRequestListener = new AuthRequestListener(this);

    public ProgressDialog progressDialog;
    private PreferencesManager preferences;

    @Override
    public void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        PreferencesManager.initializeInstance(getApplicationContext());
        context = this;
    }

    @Override
    public void onStart()
    {
        preferences = PreferencesManager.getInstance();



        spiceManager.start(this);
        super.onStart();
        isActive = true;
        addEditTextChangeListeners();

        //authRequestListener = new AuthRequestListener(this);

        /*String token = preferences.getToken();

        if(!token.equals(""))
        {
            //todo autologin
            //todo update token
        }*/
    }

    @Override
    public void onStop()
    {
        spiceManager.shouldStop();
        isActive = false;
        super.onStop();
    }

    @Click(R.id.btnLogin)
    void login()
    {
        InputMethodManager imm = (InputMethodManager)getSystemService(Context.INPUT_METHOD_SERVICE);
        imm.hideSoftInputFromWindow(txtPassword.getWindowToken(), 0);

        Boolean isValid = validateForm();

        if(isValid)
        {
            showProgressDialog();

            String username = txtUsername.getText().toString();
            String password = txtPassword.getText().toString();

            //todo get secret from? get client_id from?
            AuthObject ao = new AuthObject("webfront_webfront", username, password, "secret");
            authRequest.setCredentials(ao);

            if(this.authRequestListener == null)
            {
                init();
            }
            spiceManager.execute(authRequest, null, DurationInMillis.NEVER, authRequestListener);
            authRequestListener.requestStarted();
        }
    }

    @Override
    public void onAuthSuccessRequest(Token authResult)
    {
        progressDialog.dismiss();

        //showMsg(authResult.getAccess_token());

        Intent intent = new Intent(this, LighthouseDemoActivity_.class);
        intent.putExtra("access_token", authResult.getAccess_token());

        startActivity(intent);
        finish();
    }

    @Override
    public void onAuthFailureRequest(SpiceException spiceException)
    {
        progressDialog.dismiss();

        String msg = "";
        if(spiceException.getCause() instanceof HttpClientErrorException)
        {
            HttpClientErrorException exception = (HttpClientErrorException)spiceException.getCause();
            if(exception.getStatusCode().equals(HttpStatus.BAD_REQUEST))
            {
                //wrong credentials
                msg = getResources().getString(R.string.error_bad_credentials);
            }
            else
            {
                //other Network exception
                msg = spiceException.getMessage();
            }
        }
        else if(spiceException instanceof RequestCancelledException)
        {
            //cancelled
            msg = spiceException.getMessage();
        }
        else
        {
            //other exception
            msg = spiceException.getMessage();
        }

        Toast.makeText(context, msg, Toast.LENGTH_LONG).show();

        //final String msg = spiceException.getCause().getMessage();
    }

    private void addEditTextChangeListeners()
    {
        txtUsername.addTextChangedListener(new TextWatcher(){
            public void afterTextChanged(Editable s)
            {
                if (s.length() > 0)
                {
                    txtUsername.setError(null);
                }
            }
            public void beforeTextChanged(CharSequence s, int start, int count, int after){}
            public void onTextChanged(CharSequence s, int start, int before, int count){}
        });

        txtPassword.addTextChangedListener(new TextWatcher(){
            public void afterTextChanged(Editable s)
            {
                //if (txtUsername.getText().toString().length() > 0)
                if (s.length() > 0)
                {
                    txtPassword.setError(null);
                }
            }
            public void beforeTextChanged(CharSequence s, int start, int count, int after){}
            public void onTextChanged(CharSequence s, int start, int before, int count){}
        });
    }

    private Boolean validateForm()
    {
        Boolean result = true;

        String username = txtUsername.getText().toString();
        String password = txtPassword.getText().toString();

        if (!StringUtils.hasText(username))
        {
            txtUsername.setError(getResources().getString(R.string.error_empty_field));
            result = false;
        }

        if (!StringUtils.hasText(password))
        {
            txtPassword.setError(getResources().getString(R.string.error_empty_field));
            result = false;
        }
        return result;
    }

    @Override
    public void onBackPressed()
    {
        //if(appCanClose)
        {
            finish();
        }
    }



    protected void showProgressDialog()
    {
        progressDialog = new ProgressDialog(this);
        progressDialog.setMessage(getResources().getString(R.string.auth_dialog_title));
        progressDialog.setIndeterminate(true);
        progressDialog.setCancelable(true);
        progressDialog.show();
    }

    public void init(AuthRequestListener authRequestIdlingResource)
    {
        this.authRequestListener = authRequestIdlingResource;
    }

    public void init()
    {
        this.authRequestListener = new AuthRequestListener(this);
    }
}
