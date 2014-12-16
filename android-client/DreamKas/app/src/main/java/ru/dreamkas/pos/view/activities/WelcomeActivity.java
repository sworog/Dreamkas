package ru.dreamkas.pos.view.activities;

import android.app.Activity;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.util.DisplayMetrics;
import android.util.TypedValue;
import android.view.KeyEvent;
import android.view.View;
import android.view.ViewGroup;
import android.view.ViewTreeObserver;
import android.view.WindowManager;
import android.widget.FrameLayout;
import android.widget.LinearLayout;
import android.widget.RadioGroup;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.gc.materialdesign.utils.Utils;

import org.androidannotations.annotations.AfterViews;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EActivity;
import org.androidannotations.annotations.ViewById;
import org.apache.commons.lang3.math.Fraction;

import java.util.ArrayList;

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

        DisplayMetrics displayMetrics = this.getResources().getDisplayMetrics();
        this.getWindowManager().getDefaultDisplay().getRealMetrics(displayMetrics);
        int dpHeight = (int)Math.floor(displayMetrics.heightPixels / displayMetrics.density);
        int dpWidth = (int)Math.floor(displayMetrics.widthPixels / displayMetrics.density);

        //dirty
        if(dpHeight == 600 && dpWidth == 961){
            dpWidth = 960;
        }

        Fraction ratio = MathHelper.asFraction(dpWidth, dpHeight);

        float px = TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_DIP, 14, getResources().getDisplayMetrics());
        DreamkasApp.setRatio(ratio, dpWidth);


    }

    private ArrayList<View> getAllChildren(View v) {

        if (!(v instanceof ViewGroup)) {
            ArrayList<View> viewArrayList = new ArrayList<View>();
            viewArrayList.add(v);
            return viewArrayList;
        }

        ArrayList<View> result = new ArrayList<View>();

        ViewGroup viewGroup = (ViewGroup) v;
        for (int i = 0; i < viewGroup.getChildCount(); i++) {

            View child = viewGroup.getChildAt(i);

            ArrayList<View> viewArrayList = new ArrayList<View>();
            viewArrayList.add(v);
            viewArrayList.addAll(getAllChildren(child));

            result.addAll(viewArrayList);
        }
        return result;
    }

    @Override
    public void onStart(){
        super.onStart();




        pagerWelcome.setOnScreenSwitchListener(onScreenSwitchListener);

        radioGroup.setOnCheckedChangeListener(onCheckedChangedListener);

        btnLogin.setTextColor(getResources().getColor(R.color.ActiveMain));

        switch(DreamkasApp.getRatio()){
            case _16_10:
                pagerWelcome.setTag("norm");
                pagerWelcome.setLayoutParams(new LinearLayout.LayoutParams(WindowManager.LayoutParams.MATCH_PARENT, DreamkasApp.getDpValueInPixels(DreamkasApp.getSquareSide()*6)));
                llBottomPane.setTag("norm");
                llBottomPane.setLayoutParams(new LinearLayout.LayoutParams(WindowManager.LayoutParams.MATCH_PARENT, DreamkasApp.getDpValueInPixels(DreamkasApp.getSquareSide()*4)));
                break;
            case _3_4:
                break;
        }

        if (BuildConfig.DEBUG) {
            //Toast.makeText(this, "ratio: " + ratio + "w: " + dpWidth + " h: " + dpHeight, Toast.LENGTH_LONG).show();
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

   // @AfterViews
    void afv(){

        final View mainMenuLayout = getWindow().getDecorView().findViewById(android.R.id.content);
        getWindow().getDecorView().findViewById(android.R.id.content).getViewTreeObserver().addOnGlobalLayoutListener(
                new ViewTreeObserver.OnGlobalLayoutListener() {

                    @Override
                    public void onGlobalLayout() {

                        mainMenuLayout.getViewTreeObserver().removeGlobalOnLayoutListener(this);

                        ArrayList<View> childs = getAllChildren(mainMenuLayout);


                        float dkp = DreamkasApp.getSquareSide() / 64;
                        for(View view: childs){
                            if(view.getTag() == "norm"){
                                continue;
                            }

                            if(view instanceof TextView){

                            }

                            float width = view.getWidth() * dkp;
                            float height = view.getHeight() * dkp;

                            ViewGroup.LayoutParams lp = view.getLayoutParams();

                            if(lp instanceof LinearLayout.LayoutParams){
                                LinearLayout.LayoutParams lpNew = new LinearLayout.LayoutParams(Math.round(width), Math.round(height));
                                int ml = Math.round(((LinearLayout.LayoutParams) lp).leftMargin * dkp);
                                int mt = Math.round(((LinearLayout.LayoutParams) lp).topMargin * dkp);
                                int mr = Math.round(((LinearLayout.LayoutParams) lp).rightMargin * dkp);
                                int mb = Math.round(((LinearLayout.LayoutParams) lp).bottomMargin * dkp);
                                lpNew.setMargins(ml, mt, mr, mb);
                                lpNew.gravity = ((LinearLayout.LayoutParams) lp).gravity;

                                view.setLayoutParams(lpNew);

                            }else if(lp instanceof RelativeLayout.LayoutParams) {
                                view.setLayoutParams(new RelativeLayout.LayoutParams(Math.round(width), Math.round(height)));

                                RelativeLayout.LayoutParams lpNew = new RelativeLayout.LayoutParams(Math.round(width), Math.round(height));
                                int ml = Math.round(((RelativeLayout.LayoutParams) lp).leftMargin * dkp);
                                int mt = Math.round(((RelativeLayout.LayoutParams) lp).topMargin * dkp);
                                int mr = Math.round(((RelativeLayout.LayoutParams) lp).rightMargin * dkp);
                                int mb = Math.round(((RelativeLayout.LayoutParams) lp).bottomMargin * dkp);
                                lpNew.setMargins(ml, mt, mr, mb);

                                view.setLayoutParams(lpNew);
                            }else if(lp instanceof FrameLayout.LayoutParams){
                                view.setLayoutParams(new FrameLayout.LayoutParams(Math.round(width), Math.round(height)));

                                FrameLayout.LayoutParams lpNew = new FrameLayout.LayoutParams(Math.round(width), Math.round(height));
                                int ml = Math.round(((FrameLayout.LayoutParams) lp).leftMargin * dkp);
                                int mt = Math.round(((FrameLayout.LayoutParams) lp).topMargin * dkp);
                                int mr = Math.round(((FrameLayout.LayoutParams) lp).rightMargin * dkp);
                                int mb = Math.round(((FrameLayout.LayoutParams) lp).bottomMargin * dkp);
                                lpNew.setMargins(ml, mt, mr, mb);
                                lpNew.gravity = ((FrameLayout.LayoutParams) lp).gravity;
                                view.setLayoutParams(lpNew);
                            }

                            else if(lp instanceof ViewGroup.MarginLayoutParams) {
                                view.setLayoutParams(new ViewGroup.MarginLayoutParams(Math.round(width), Math.round(height)));

                                ViewGroup.MarginLayoutParams lpNew = new ViewGroup.MarginLayoutParams(Math.round(width), Math.round(height));
                                int ml = Math.round(((ViewGroup.MarginLayoutParams) lp).leftMargin * dkp);
                                int mt = Math.round(((ViewGroup.MarginLayoutParams) lp).topMargin * dkp);
                                int mr = Math.round(((ViewGroup.MarginLayoutParams) lp).rightMargin * dkp);
                                int mb = Math.round(((ViewGroup.MarginLayoutParams) lp).bottomMargin * dkp);
                                lpNew.setMargins(ml, mt, mr, mb);


                                view.setLayoutParams(lpNew);
                            }
                            else {
                                view.setLayoutParams(new ViewGroup.LayoutParams(Math.round(width), Math.round(height)));

                            }
                            //ViewGroup.LayoutParams lp = view.getLayoutParams();
                        }


                    }
                });


    }

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
