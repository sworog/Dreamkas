package ru.dreamkas.pos.view.components;

import android.app.Activity;
import android.content.Context;
import android.graphics.Color;
import android.support.v4.widget.DrawerLayout;
import android.util.AttributeSet;
import android.view.GestureDetector;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.view.ViewTreeObserver;
import android.widget.Button;
import android.widget.LinearLayout;

import ru.dreamkas.pos.R;

//@EViewGroup(R.layout.product_search_component)
public class ConfirmButtonComponent extends Button implements View.OnClickListener {
    private String mConfirmText = "Подтвердить очистку чека";
    private CharSequence mRegularText;
    private OnClickListener mExternalOnClickListener;
    private State mCurrentState = State.REGULAR;
    private ViewGroup mLayout;

    public ConfirmButtonComponent(Context context) {
        super(context);
        init();
    }

    public ConfirmButtonComponent(Context context, AttributeSet attrs) {
        super(context, attrs);
        init();
    }

    public ConfirmButtonComponent(Context context, AttributeSet attrs, int defStyle) {
        super(context, attrs, defStyle);
        init();
    }

    public enum State{
        REGULAR, WAIT_FOR_CONFIRM, CONFIRMED;
    }

    private void init(){
        super.setOnClickListener(this);
    }

    public void setContainer(ViewGroup parentContainer){
        mLayout = parentContainer;
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

        final ConfirmButtonComponent thisBtn = this;

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
                mLayout.setVisibility(VISIBLE);

                mLayout.setOnTouchListener(new OnTouchListener() {
                    @Override
                    public boolean onTouch(final View view, final MotionEvent event) {
                        int x = (int) event.getX();
                        int y = (int) event.getY();

                        if (mCurrentState == State.WAIT_FOR_CONFIRM) {
                            int[] coors = new int[]{0,0,0,0};

                            //return wrong Y-coors
                            thisBtn.getLocationOnScreen(coors);

                            coors[2] = coors[0] + thisBtn.getWidth();
                            //dirty replace wrong Y-coors
                            coors[1] = thisBtn.getTop();
                            coors[3] = thisBtn.getBottom();

                            if (!isPointWithin(x, y, coors[0], coors[1], coors[2], coors[3]))
                            {
                                changeState(State.REGULAR);
                                view.setVisibility(GONE);
                            }
                        }
                        return false;
                    }
                });

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

    @Override
    public void setOnClickListener(View.OnClickListener listener){
        mExternalOnClickListener = listener;
    }

    public void setConfirmationText(String confirm) {
        mConfirmText = confirm;
    }
}
