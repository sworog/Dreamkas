package ru.dreamkas.pos.view.components;

import android.annotation.TargetApi;
import android.app.ActionBar;
import android.content.Context;
import android.content.res.TypedArray;
import android.graphics.Color;
import android.graphics.drawable.BitmapDrawable;
import android.os.Build;
import android.os.Handler;
import android.util.AttributeSet;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.Window;
import android.widget.Button;
import android.widget.PopupWindow;

import com.gc.materialdesign.views.ButtonFlat;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.view.components.regular.ButtonFlatExt;

public class ConfirmButtonComponent extends ButtonFlatExt implements View.OnClickListener {
    private String mConfirmText = "Подтвердить очистку чека";
    private CharSequence mRegularText;
    private OnClickListener mExternalOnClickListener;
    private State mCurrentState = State.REGULAR;
    private Window.Callback mRealTouchOwner;
    private PopupWindow pw;

    public enum State{
        REGULAR, WAIT_FOR_CONFIRM, CONFIRMED;
    }

    /*public ConfirmButtonComponent(Context context) {
        super(context);
        init();
    }*/

    public ConfirmButtonComponent(Context context, AttributeSet attrs) {
        super(context, attrs);
        setAttributes(context, attrs);
        init();
    }

    /*public ConfirmButtonComponent(Context context, AttributeSet attrs, int defStyle) {
        super(context, attrs, defStyle);
        setAttributes(context, attrs);
        init();
    }*/

    @Override
    public void onClick(View v) {
        switch (mCurrentState){
            case REGULAR:
                changeState(State.WAIT_FOR_CONFIRM);
                break;
            case WAIT_FOR_CONFIRM:
                changeState(State.CONFIRMED);
                break;
        }
    }

    @Override
    public void setOnClickListener(View.OnClickListener listener){
        mExternalOnClickListener = listener;
    }

    private void setAttributes(Context context, AttributeSet attrs) {
        TypedArray a = context.obtainStyledAttributes(attrs, R.styleable.confirm_button_component);
        CharSequence s = a.getString(R.styleable.confirm_button_component_confirmation_text);
        if (s != null) {
            mConfirmText = s.toString();
        }
        a.recycle();
    }

    private void init(){
        super.setOnClickListener(this);
    }

    public void setTouchOwner(Window.Callback dispatcherTarget) {
        mRealTouchOwner = dispatcherTarget;
    }

    public void changeState(final State state){
        if(state == mCurrentState){
            return;
        }

        final ConfirmButtonComponent thisBtn = this;

        switch (state){
            case REGULAR:
                getTextView().setTextColor(Color.BLACK);
                getTextView().setText(mRegularText);
                mCurrentState = state;

                break;
            case WAIT_FOR_CONFIRM:
                getTextView().setTextColor(Color.RED);
                mRegularText = getText();
                setText(mConfirmText);

                View transparentLayerView = LayoutInflater.from(getContext()).inflate(R.layout.empty_container, null, false);
                pw = new PopupWindow(transparentLayerView, ActionBar.LayoutParams.MATCH_PARENT, ActionBar.LayoutParams.MATCH_PARENT);

                pw.setBackgroundDrawable (new BitmapDrawable());
                pw.setFocusable(false);
                pw.setOutsideTouchable(true);

                pw.setTouchInterceptor(new OnTouchListener() {
                    @TargetApi(Build.VERSION_CODES.KITKAT)
                    @Override
                    public boolean onTouch(View v, MotionEvent event) {
                        int x = (int) event.getRawX();
                        int y = (int) event.getRawY();

                        if (mCurrentState == State.WAIT_FOR_CONFIRM) {
                            int[] buttonCoors = new int[]{0,0, 0, 0};
                            thisBtn.getLocationOnScreen(buttonCoors);
                            buttonCoors[2] = buttonCoors[0] + thisBtn.getWidth();
                            buttonCoors[3] = buttonCoors[1] + thisBtn.getHeight();
                            if (!isPointWithin(x, y, buttonCoors[0], buttonCoors[1], buttonCoors[2], buttonCoors[3]))
                            {
                                changeState(State.REGULAR);
                            }
                        }
                        if(mRealTouchOwner != null){
                            MotionEvent cp = MotionEvent.obtain(event);
                            cp.recycle();
                            mRealTouchOwner.dispatchTouchEvent(cp);
                        }else {
                            throw new IllegalStateException("You should call setTouchOwner() firstly");
                        }

                        new Handler().postDelayed(new Runnable() {
                            public void run() {
                                if(pw != null){
                                    pw.dismiss();

                                    /*if(state != State.CONFIRMED){
                                        changeState(State.REGULAR);
                                    }*/
                                }
                            }
                        }, 200);

                        return false;
                    }
                });

                pw.showAtLocation(getRootView(), Gravity.NO_GRAVITY, 0, 0);

                mCurrentState = state;
                break;
            case CONFIRMED:
                if(mExternalOnClickListener != null){
                    mExternalOnClickListener.onClick(this);
                }
                changeState(State.REGULAR);
        }
    }

    static boolean isPointWithin(int x, int y, int x1, int y1, int x2, int y2) {
        return (x <= x2 && x >= x1 && y <= y2 && y >= y1);
    }

    public void setConfirmationText(String confirm) {
        mConfirmText = confirm;
    }
}
