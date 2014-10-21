package ru.dreamkas.pos.view.components;

import android.content.Context;
import android.graphics.Color;
import android.util.AttributeSet;
import android.view.View;
import android.widget.Button;

//@EViewGroup(R.layout.product_search_component)
public class ConfirmButtonComponent extends Button implements View.OnClickListener {
    private String mConfirmText = "Подтвердить очистку чека";
    private CharSequence mRegularText;
    private OnClickListener mExternalOnClickListener;
    private State mCurrentState = State.REGULAR;

    public ConfirmButtonComponent(Context context) {
        super(context);
        init();
    }

    public ConfirmButtonComponent(Context context, AttributeSet attrs) {
        super(context, attrs);
        init();

       /* TypedArray a = context.getTheme().obtainStyledAttributes(
                attrs,
                R.styleable.ConfirmButtonComponent,
                0, 0);
        try {
            Boolean mShowText = a.getBoolean(R.styleable.ConfirmButtonComponent_showText, false);
        } finally {
            a.recycle();
        }*/
    }

    public ConfirmButtonComponent(Context context, AttributeSet attrs, int defStyle) {
        super(context, attrs, defStyle);
        init();

        /*TypedArray a = context.getTheme().obtainStyledAttributes(
                attrs,
                R.styleable.ConfirmButtonComponent,
                0, 0);
        try {
            Boolean mShowText = a.getBoolean(R.styleable.ConfirmButtonComponent_showText, false);
        } finally {
            a.recycle();
        }*/
    }

    public enum State{
        REGULAR, WAIT_FOR_CONFIRM, CONFIRMED;
    }

    private void init(){
        super.setOnClickListener(this);
    }

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

    public void changeState(State state){
        if(state == mCurrentState){
            return;
        }

        switch (state){
            case REGULAR:
                setTextColor(Color.DKGRAY);
                setText(mRegularText);
                mCurrentState = state;
                break;
            case WAIT_FOR_CONFIRM:
                setTextColor(Color.RED);
                mRegularText = getText();
                setText(mConfirmText);
                mCurrentState = state;
                break;
            case CONFIRMED:
                if(mExternalOnClickListener != null){
                    mExternalOnClickListener.onClick(this);
                }
                changeState(State.REGULAR);
        }
    }

    @Override
    public void setOnClickListener(View.OnClickListener listener){
        mExternalOnClickListener = listener;
    }

    public void setConfirmationText(String confirm) {
        mConfirmText = confirm;
    }
}
