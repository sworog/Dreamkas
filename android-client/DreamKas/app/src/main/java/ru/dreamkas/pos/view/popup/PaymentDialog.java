package ru.dreamkas.pos.view.popup;

import android.animation.Animator;
import android.animation.AnimatorListenerAdapter;
import android.app.Dialog;
import android.content.Context;
import android.text.Editable;
import android.text.SpannableStringBuilder;
import android.text.TextWatcher;
import android.view.MotionEvent;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.ImageButton;
import android.widget.LinearLayout;
import android.widget.TextView;

import java.math.BigDecimal;

import ru.dreamkas.pos.Constants;
import ru.dreamkas.pos.DreamkasApp;
import ru.dreamkas.pos.R;
import ru.dreamkas.pos.model.Receipt;
import ru.dreamkas.pos.view.components.NumericEditText;
import ru.dreamkas.pos.view.components.regular.ButtonRectangleExt;
import ru.dreamkas.pos.view.components.regular.TextViewTypefaced;
import ru.dreamkas.pos.view.misc.StringDecorator;

public class PaymentDialog extends Dialog {

    private TextViewTypefaced lblTotal;
    private TextViewTypefaced lblDone;
    private TextViewTypefaced lblInfo;

    private DialogResult result = DialogResult.Cancel;
    private ImageButton btnClose;

    private Receipt mReceipt;

    private NumericEditText txtCash;
    private ButtonRectangleExt btnSellWithCash;
    private ButtonRectangleExt btnSellWithCard;
    private LinearLayout llDone;
    private LinearLayout llMain;
    private LinearLayout llHeader;
    private ButtonRectangleExt btnNewReceipt;

    public enum DialogResult{Pay, Cancel;}

    public PaymentDialog(Context context, Receipt receipt) {
        super(context, R.style.dialog_slide_anim);
        setReceipt(receipt);
    }

    @Override
    public void show(){
        getWindow().setBackgroundDrawableResource(android.R.color.transparent);
        requestWindowFeature(Window.FEATURE_NO_TITLE);
        setContentView(R.layout.payment_dialog);
        setCanceledOnTouchOutside(true);

        WindowManager.LayoutParams lp = new WindowManager.LayoutParams();
        lp.copyFrom(getWindow().getAttributes());
        lp.width = 800;
        lp.height = WindowManager.LayoutParams.MATCH_PARENT;

        super.show();

        getWindow().setAttributes(lp);

        this.init();
    }

    @Override
    public boolean onTouchEvent(MotionEvent event) {
        return false;
    }

    @Override
    public boolean dispatchTouchEvent(MotionEvent ev) {
        return super.dispatchTouchEvent(ev);
    }

    @Override
    public void cancel(){
        result = DialogResult.Cancel;
        super.cancel();
    }

    private void init() {

        btnSellWithCash = (ButtonRectangleExt) findViewById(R.id.btnSellWithCash);
        btnSellWithCash.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                sellWithCash(100);
            }
        });

        btnSellWithCard = (ButtonRectangleExt) findViewById(R.id.btnSellWithCard);
        btnSellWithCard.setRippleSpeed(20);
        btnSellWithCard.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                sellWithCard();
            }
        });

        lblTotal = (TextViewTypefaced) findViewById(R.id.lblTotal);
        lblDone = (TextViewTypefaced) findViewById(R.id.lblDone);
        lblInfo = (TextViewTypefaced) findViewById(R.id.lblInfo);

        calcTotal();

        llDone = (LinearLayout) findViewById(R.id.llDone);
        llMain = (LinearLayout) findViewById(R.id.llMain);
        llHeader = (LinearLayout) findViewById(R.id.llHeader);

        btnClose = (ImageButton) findViewById(R.id.btnCloseModal);
        btnClose.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                cancel();
            }
        });

        btnNewReceipt = (ButtonRectangleExt) findViewById(R.id.btnNewReceipt);
        btnNewReceipt.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                done();
            }
        });

        txtCash = (NumericEditText) findViewById(R.id.txtCash);
        txtCash.setAllowEmptyValue(true);
        addCashChangeListeners();

        validate();
    }

    private void sellWithCash(double change) {
        SpannableStringBuilder changeStr = StringDecorator.buildStringWithRubleSymbol(DreamkasApp.getResourceString(R.string.msg_info_ruble_value), DreamkasApp.getMoneyFormat().format(change), StringDecorator.RUBLE_CODE);
        lblDone.setText(changeStr);
        String changeFrom = DreamkasApp.getResourceString(R.string.msg_info_change_from).format(DreamkasApp.getMoneyFormat().format(mReceipt.getTotal()));
        lblInfo.setText(changeFrom);
        sell();
    }

    private void sellWithCard() {
        lblInfo.setVisibility(View.GONE);
        sell();
    }

    private void sell() {
        llHeader.animate()
                .alpha(0f)
                .setDuration(500)
                .setListener(new AnimatorListenerAdapter() {
                    @Override
                    public void onAnimationEnd(Animator animation) {
                        llMain.setVisibility(View.GONE);
                    }
                });

        btnClose.animate()
                .alpha(0f)
                .setDuration(500)
                .setListener(new AnimatorListenerAdapter() {
                    @Override
                    public void onAnimationEnd(Animator animation) {
                        llMain.setVisibility(View.GONE);
                    }
                });

        crossfadding(llDone);
    }

    private void crossfadding(LinearLayout target) {
        target.setAlpha(0f);
        target.setVisibility(View.VISIBLE);

        target.animate()
                .alpha(1f)
                .setDuration(500)
                .setListener(null);

        llMain.animate()
                .alpha(0f)
                .setDuration(500)
                .setListener(new AnimatorListenerAdapter() {
                    @Override
                    public void onAnimationEnd(Animator animation) {
                        llMain.setVisibility(View.GONE);
                    }
                });

    }


    private void calcTotal() {
        SpannableStringBuilder total = StringDecorator.buildStringWithRubleSymbol(DreamkasApp.getResourceString(R.string.msg_info_ruble_value), DreamkasApp.getMoneyFormat().format(mReceipt.getTotal()), StringDecorator.RUBLE_CODE);
        lblTotal.setText(total);
    }

    public void setReceipt(Receipt receipt) {
        this.mReceipt = receipt;
    }

    private void addCashChangeListeners(){
        txtCash.addTextChangedListener(new TextWatcher(){
            public void afterTextChanged(Editable s){
                validate();
            }
            public void beforeTextChanged(CharSequence s, int start, int count, int after){}
            public void onTextChanged(CharSequence s, int start, int before, int count){}
        });
    }

    public void done(){
        result = DialogResult.Pay;
        dismiss();
    }

    public DialogResult getResult(){
        return result;
    }

    private void validate() {
        if(txtCash.getValue() == null || mReceipt.getTotal().compareTo(txtCash.getValue()) == Constants.DECIMAL_COMPARE_RESULT_BASE_GREATER){
            btnSellWithCash.setEnabled(false);
        }else {
            btnSellWithCash.setEnabled(true);
        }
    }
}

