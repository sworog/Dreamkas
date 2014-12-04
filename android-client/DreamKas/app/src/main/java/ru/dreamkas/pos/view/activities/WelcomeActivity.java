package ru.dreamkas.pos.view.activities;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.View;
import android.widget.RadioGroup;

import ru.dreamkas.pos.view.components.regular.ButtonRectangleExt;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EActivity;
import org.androidannotations.annotations.ViewById;
import ru.dreamkas.pos.R;
import ru.dreamkas.pos.controller.PreferencesManager;
import ru.dreamkas.pos.view.components.HorizontalPager;
import ru.dreamkas.pos.view.popup.LoginDialog;

@EActivity(R.layout.welcome_activity)
public class WelcomeActivity extends Activity {
    public boolean isActive = false;

    private Context context;

    @ViewById
    HorizontalPager pagerWelcome;

    @ViewById
    RadioGroup radioGroup;

    @ViewById
    ButtonRectangleExt btnLogin;
    private boolean mDialogInProgress;

    @Override
    public void onCreate(Bundle savedInstanceState){
        super.onCreate(savedInstanceState);
        PreferencesManager.initializeInstance(getApplicationContext());
    }

    @Override
    public void onStart(){
        super.onStart();
        pagerWelcome.setOnScreenSwitchListener(onScreenSwitchListener);

        radioGroup.setOnCheckedChangeListener(onCheckedChangedListener);

        btnLogin.setTextColor(getResources().getColor(R.color.ActiveMain));
    }

    @Click(R.id.btnLogin)
    void login(){
        if(mDialogInProgress){
            return ;
        }
        mDialogInProgress = true;

        final LoginDialog dialog = new LoginDialog(this);
        final Context context = this;
        dialog.show();
        dialog.setOnDismissListener(new DialogInterface.OnDismissListener() {
            @Override
            public void onDismiss(final DialogInterface arg0) {
                mDialogInProgress = false;
                switch (dialog.getResult()){
                    case Login:
                        Intent intent = new Intent(context, MainActivity_.class);
                        intent.putExtra("access_token", dialog.getAccessToken());
                        startActivity(intent);
                        break;
                    case Cancel:
                        break;
                }
            }
        });
    }

    private final HorizontalPager.OnScreenSwitchListener onScreenSwitchListener =
            new HorizontalPager.OnScreenSwitchListener() {
                @Override
                public void onScreenSwitched(final int screen) {
                    // Check the appropriate button when the user swipes screens.
                    switch (screen) {
                        case 0:
                            radioGroup.check(R.id.radio_btn_0);
                            break;
                        case 1:
                            radioGroup.check(R.id.radio_btn_1);
                            break;
                        case 2:
                            radioGroup.check(R.id.radio_btn_2);
                            break;
                        default:
                            break;
                    }
                }
            };

    private final RadioGroup.OnCheckedChangeListener onCheckedChangedListener =
            new RadioGroup.OnCheckedChangeListener() {
                @Override
                public void onCheckedChanged(final RadioGroup group, final int checkedId) {
                    // Slide to the appropriate screen when the user checks a button.
                    switch (checkedId) {
                        case R.id.radio_btn_0:
                            pagerWelcome.setCurrentScreen(0, true);
                            break;
                        case R.id.radio_btn_1:
                            pagerWelcome.setCurrentScreen(1, true);
                            break;
                        case R.id.radio_btn_2:
                            pagerWelcome.setCurrentScreen(2, true);
                            break;
                        default:
                            break;
                    }
                }
            };

    private Runnable decor_view_settings = new Runnable()
    {
        public void run()
        {
            getWindow().getDecorView().setSystemUiVisibility(
                    View.SYSTEM_UI_FLAG_LAYOUT_STABLE
                            | View.SYSTEM_UI_FLAG_LAYOUT_HIDE_NAVIGATION
                            | View.SYSTEM_UI_FLAG_LAYOUT_FULLSCREEN
                            | View.SYSTEM_UI_FLAG_HIDE_NAVIGATION
                            | View.SYSTEM_UI_FLAG_FULLSCREEN
                            | View.SYSTEM_UI_FLAG_IMMERSIVE_STICKY);
        }
    };

    @Override
    public void onWindowFocusChanged(boolean hasFocus)
    {
        super.onWindowFocusChanged(hasFocus);

        if(hasFocus)
        {
            getWindow().getDecorView().post(decor_view_settings);
        }
    }

    @Override
    public boolean onKeyDown(int keyCode, KeyEvent event)
    {
        if(keyCode == KeyEvent.KEYCODE_BACK ||keyCode == KeyEvent.KEYCODE_VOLUME_DOWN || keyCode == KeyEvent.KEYCODE_VOLUME_UP)
        {
            getWindow().getDecorView().postDelayed(decor_view_settings, 500);
        }
        return super.onKeyDown(keyCode, event);
    }

}
