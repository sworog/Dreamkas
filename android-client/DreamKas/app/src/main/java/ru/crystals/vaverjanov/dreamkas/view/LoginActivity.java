package ru.crystals.vaverjanov.dreamkas.view;

import android.app.Activity;
import android.content.Intent;
import android.widget.EditText;
import android.widget.Toast;

import com.octo.android.robospice.SpiceManager;
import com.octo.android.robospice.persistence.DurationInMillis;
import com.octo.android.robospice.persistence.exception.SpiceException;
import com.octo.android.robospice.request.listener.RequestListener;

import org.androidannotations.annotations.Bean;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EActivity;
import org.androidannotations.annotations.ViewById;
import org.springframework.util.StringUtils;

import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.controller.AuthRequest;
import ru.crystals.vaverjanov.dreamkas.controller.LighthouseSpiceService;
import ru.crystals.vaverjanov.dreamkas.controller.listeners.AuthRequestActivity;
import ru.crystals.vaverjanov.dreamkas.controller.listeners.AuthRequestListener;
import ru.crystals.vaverjanov.dreamkas.model.AuthObject;
import ru.crystals.vaverjanov.dreamkas.model.Token;

@EActivity(R.layout.activity_login)
public class LoginActivity extends Activity implements AuthRequestActivity {

    @ViewById
    EditText txtPassword;

    @ViewById
    EditText txtUsername;

    @Bean
    public AuthRequest authRequest;

    private SpiceManager spiceManager = new SpiceManager(LighthouseSpiceService.class);
    public final AuthRequestListener authRequestListener = new AuthRequestListener(this);

    @Override
    public void onStart()
    {
        spiceManager.start(this);
        super.onStart();
    }

    @Override
    public void onStop()
    {
        spiceManager.shouldStop();
        super.onStop();
    }

    @Click(R.id.btnLogin)
    void login()
    {
        String username = txtUsername.getText().toString();
        String password = txtPassword.getText().toString();

        if (!StringUtils.hasText(username) && !StringUtils.hasText(password))
        {
            showMsg("Пожалуйста, заполните обязательные поля email и пароль");
            return;
        }

        setProgressBarIndeterminateVisibility(true);

        //todo get secret from? get client_id from?
        AuthObject ao = new AuthObject("webfront_webfront", username, password, "secret");
        authRequest.setCredentials(ao);
        spiceManager.execute(authRequest, null, DurationInMillis.NEVER, authRequestListener);
    }

    private void showMsg(String msg)
    {
        Toast.makeText(this, msg, Toast.LENGTH_SHORT).show();
    }

    @Override
    public void onAuthSuccessRequest(Token authResult)
    {
        setProgressBarIndeterminateVisibility(false);

        showMsg(authResult.getAccess_token());

        Intent intent = new Intent(this, LighthouseDemoActivity_.class);
        intent.putExtra("access_token", authResult.getAccess_token());

        startActivity(intent);
    }

    @Override
    public void onAuthFailureRequest(SpiceException spiceException)
    {
        setProgressBarIndeterminateVisibility(false);
        final String msg = spiceException.getCause().getMessage();
        showMsg("Error: " + msg);
    }
}
