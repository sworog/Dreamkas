package ru.dreamkas.pos.view.activities;

import android.app.Activity;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.util.DisplayMetrics;
import android.view.KeyEvent;
import android.view.View;
import android.view.WindowManager;
import android.widget.LinearLayout;
import android.widget.RadioGroup;
import android.widget.Toast;

import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EActivity;
import org.androidannotations.annotations.ViewById;
import org.apache.commons.lang3.math.Fraction;

import ru.dreamkas.pos.BuildConfig;
import ru.dreamkas.pos.DreamkasApp;
import ru.dreamkas.pos.MathHelper;
import ru.dreamkas.pos.R;
import ru.dreamkas.pos.controller.PreferencesManager;
import ru.dreamkas.pos.view.components.HorizontalPager;
import ru.dreamkas.pos.view.components.regular.ButtonRectangleExt;
import ru.dreamkas.pos.view.popup.LoginDialog_;

@EActivity(R.layout.welcome_activity)
public class WelcomeActivity extends Activity {
    public boolean isActive = false;

    @ViewById
    HorizontalPager pagerWelcome;
    @ViewById
    LinearLayout llBottomPane;

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

        DisplayMetrics displayMetrics = this.getResources().getDisplayMetrics();
        this.getWindowManager().getDefaultDisplay().getRealMetrics(displayMetrics);
        int dpHeight = (int)Math.floor(displayMetrics.heightPixels / displayMetrics.density);
        int dpWidth = (int)Math.floor(displayMetrics.widthPixels / displayMetrics.density);

        //dirty
        if(dpHeight == 600 && dpWidth == 961){
            dpWidth = 960;
        }

        Fraction ratio = MathHelper.asFraction(dpWidth, dpHeight);

        DreamkasApp.setRatio(ratio, dpWidth);

        switch(DreamkasApp.getRatio()){
            case _16_10:
                pagerWelcome.setLayoutParams(new LinearLayout.LayoutParams(WindowManager.LayoutParams.MATCH_PARENT, DreamkasApp.getDpValueInPixels(DreamkasApp.getSquareSide()*6)));
                llBottomPane.setLayoutParams(new LinearLayout.LayoutParams(WindowManager.LayoutParams.MATCH_PARENT, DreamkasApp.getDpValueInPixels(DreamkasApp.getSquareSide()*4)));
                break;
            case _3_4:
                break;
        }

        if (BuildConfig.DEBUG) {
            Toast.makeText(this, "ratio: " + ratio + "w: " + dpWidth + " h: " + dpHeight, Toast.LENGTH_LONG).show();
        }
    }

    @Click(R.id.btnLogin)
    void login(){
        if(mDialogInProgress){
            return ;
        }
        mDialogInProgress = true;

        final LoginDialog_ dialog = new LoginDialog_();

        dialog.show(getFragmentManager(), "login_dialog");

        dialog.setOnDismissListener(new DialogInterface.OnDismissListener() {
            @Override
            public void onDismiss(final DialogInterface arg0) {
                mDialogInProgress = false;
                switch (dialog.getResult()){
                    case OK:
                        Intent intent = new Intent(getBaseContext(), MainActivity_.class);
                        intent.putExtra("access_token", dialog.getAccessToken());
                        startActivity(intent);
                        break;
                    case CANCEL:
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
