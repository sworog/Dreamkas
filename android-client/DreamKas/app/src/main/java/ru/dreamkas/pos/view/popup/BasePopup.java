package ru.dreamkas.pos.view.popup;

import android.content.Context;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.WindowManager;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.Button;
import android.widget.EditText;
import android.widget.PopupWindow;
import ru.dreamkas.pos.R;
import ru.dreamkas.pos.view.components.HorizontalPager;

public class BasePopup extends PopupWindow {

    Context context;
    EditText et_name, et_number;
    private HorizontalPager mPager;
    private OnSubmitListener mListener;

    public BasePopup(Context ctx, View inflate, OnSubmitListener listener) {
        super(inflate, 800, 752, true);

        setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_ADJUST_RESIZE);
        setWidth(WindowManager.LayoutParams.MATCH_PARENT);
        setHeight(WindowManager.LayoutParams.MATCH_PARENT);
        setFocusable(true);

        context = ctx;
        mListener = listener;

        setContentView(inflate);
        View popupView = getContentView();

        Button btn_close = (Button) popupView.findViewById(R.id.btnYes);
        Button btn_submit = (Button) popupView.findViewById(R.id.btnNo);

        mPager = (HorizontalPager) popupView.findViewById(R.id.horizontal_pager);

        mPager.setOnScreenSwitchListener(new HorizontalPager.OnScreenSwitchListener() {
            @Override
            public void onScreenSwitched(int screen) {
                if(screen == 0 && mPager.getChildCount() > 1){
                    mPager.removeViewAt(1);
                }
            }
        });
        et_name = (EditText) popupView.findViewById(R.id.editText1);
        et_number = (EditText) popupView.findViewById(R.id.editText2);

        btn_submit.setOnClickListener(new OnClickListener() {

            public void onClick(View v) {
                String name = et_name.getText().toString();
                String number = et_number.getText().toString();

                if(mListener != null){
                    mListener.valueChanged(name, number);//To change the value of the textview of activity.
                }

                mPager.addView(LayoutInflater.from(context).inflate(R.layout.credit_card_paid, null, false));
                mPager.setCurrentScreen(1, true);
            }
        });

        btn_close.setOnClickListener(new OnClickListener() {
            public void onClick(View arg0) {
                mPager.addView(LayoutInflater.from(context).inflate(R.layout.cash_paid, null, false));
                mPager.setCurrentScreen(1, true);
            }
        });
    }

    public void show(View v) {
        showAtLocation(v, Gravity.NO_GRAVITY, 0, 0);

        Animation bottomUp = AnimationUtils.loadAnimation(context,R.anim.bottom_up);

        View view = mPager.getChildAt(0);
        view.startAnimation(bottomUp);
        view.setVisibility(View.VISIBLE);
    }

    public interface OnSubmitListener {
        void valueChanged(String name, String number);
    }
}

