package ru.dreamkas.pos.view.popup;

import android.animation.Animator;
import android.animation.AnimatorListenerAdapter;
import android.content.Context;
import android.text.Editable;
import android.text.SpannableStringBuilder;
import android.text.TextWatcher;
import android.view.View;
import android.view.inputmethod.InputMethodManager;
import android.widget.ImageButton;
import android.widget.LinearLayout;
import android.widget.ProgressBar;

import com.octo.android.robospice.persistence.exception.SpiceException;
import org.androidannotations.annotations.Bean;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EFragment;
import org.androidannotations.annotations.ViewById;
import ru.dreamkas.pos.Constants;
import ru.dreamkas.pos.DreamkasApp;
import ru.dreamkas.pos.R;
import ru.dreamkas.pos.controller.Command;
import ru.dreamkas.pos.controller.PreferencesManager;
import ru.dreamkas.pos.controller.requests.AuthorisedRequestWrapper;
import ru.dreamkas.pos.controller.requests.RegisterReceiptRequest;
import ru.dreamkas.pos.model.Receipt;
import ru.dreamkas.pos.model.api.SaleApiObject;
import ru.dreamkas.pos.view.components.NumericEditText;
import ru.dreamkas.pos.view.components.regular.ButtonRectangleExt;
import ru.dreamkas.pos.view.components.regular.TextViewTypefaced;
import ru.dreamkas.pos.view.misc.StringDecorator;

@EFragment(R.layout.payment_dialog)
public class PaymentDialog extends AuthRequestContainingDialog {

    @ViewById
    TextViewTypefaced lblTotal;
    @ViewById
    TextViewTypefaced lblDone;
    @ViewById
    TextViewTypefaced lblInfo;
    @ViewById
    LinearLayout llDone;
    @ViewById
    LinearLayout llContent;
    @ViewById
    LinearLayout llHeader;

    @ViewById
    NumericEditText txtCash;

    @ViewById
    ImageButton btnCloseModal;

    @ViewById
    ButtonRectangleExt btnSellWithCash;

    @ViewById
    ButtonRectangleExt btnSellWithCard;

    @ViewById
    ProgressBar pbRegister;

    @Bean
    protected AuthorisedRequestWrapper mRegisterReceiptRequestWrapped;

    private Receipt mReceipt;

    public PaymentDialog() {
        super();
    }

    @Override
    public void onStart(){
        super.onStart();
        btnSellWithCard.setRippleSpeed(20);
        calcTotal();
        txtCash.setAllowEmptyValue(true);
        addCashChangeListeners();
        validate();
    }

    @Override
    @Click(R.id.btnCloseModal)
    public void cancel(){
        super.cancel();
    }

    @Override
    @Click(R.id.btnNewReceipt)
    public void done(){
        super.done();
    }

    @Click(R.id.btnSellWithCash)
    void sellWithCash() {
        mReceipt.setPaymentMethod(Receipt.PaymentMethod.CASH);
        mReceipt.setAmountTendered(txtCash.getValue());
        registerReceipt();
    }

    @Click(R.id.btnSellWithCard)
    void sellWithCard() {
        mReceipt.setPaymentMethod(Receipt.PaymentMethod.BANCCARD);
        registerReceipt();
    }

    private void registerReceipt() {
        InputMethodManager imm = (InputMethodManager)getActivity().getSystemService(Context.INPUT_METHOD_SERVICE);
        imm.hideSoftInputFromWindow(txtCash.getWindowToken(), 0);

        showProgressDialog("Регистрация продажи...");
        RegisterReceiptRequest request = new RegisterReceiptRequest();
        request.setReceipt(mReceipt);
        request.setStore(PreferencesManager.getInstance().getCurrentStore());

        mRegisterReceiptRequestWrapped.init(getSpiceManager(), request, getToken());
        mRegisterReceiptRequestWrapped.execute(new registerReceiptRequestSuccessFinishCommand(), new registerReceiptRequestFailureFinishCommand());
    }

    @Override
    protected void showProgressDialog(String text){
        pbRegister.setVisibility(View.VISIBLE);
    }

    @Override
    protected void stopProgressDialog(){
        pbRegister.setVisibility(View.GONE);
    }

    public class registerReceiptRequestSuccessFinishCommand implements Command<SaleApiObject> {
        public void execute(SaleApiObject data){
            stopProgressDialog();
            sold(data.getPayment().getChange());
        }
    }

    public class registerReceiptRequestFailureFinishCommand implements Command<SpiceException>{
        public void execute(SpiceException spiceException){
            stopProgressDialog();
            showRequestErrorToast(spiceException);
        }
    }

    private void sold(Double change) {
        if(change != null){
            SpannableStringBuilder changeStr = StringDecorator.buildStringWithRubleSymbol(DreamkasApp.getResourceString(R.string.msg_info_ruble_value), DreamkasApp.getMoneyFormat().format(change), StringDecorator.RUBLE_CODE);
            lblDone.setText(changeStr);

            String enderedAmount = DreamkasApp.getMoneyFormat().format(mReceipt.getAmountTendered());
            String changeFrom = String.format(DreamkasApp.getResourceString(R.string.msg_info_change_from), enderedAmount);
            lblInfo.setText(changeFrom);
        }else {
            lblInfo.setVisibility(View.GONE);
        }
        llHeader.animate()
                .alpha(0f)
                .setDuration(1000)
                .setListener(new AnimatorListenerAdapter() {
                    @Override
                    public void onAnimationEnd(Animator animation) {
                        llContent.setVisibility(View.GONE);
                    }
                });

        btnCloseModal.animate()
                .alpha(0f)
                .setDuration(1000)
                .setListener(new AnimatorListenerAdapter() {
                    @Override
                    public void onAnimationEnd(Animator animation) {
                        llContent.setVisibility(View.GONE);
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

        llContent.animate()
                .alpha(0f)
                .setDuration(500)
                .setListener(new AnimatorListenerAdapter() {
                    @Override
                    public void onAnimationEnd(Animator animation) {
                        llContent.setVisibility(View.GONE);
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

    private void validate() {
        if(txtCash.getValue() == null || mReceipt.getTotal().compareTo(txtCash.getValue()) == Constants.DECIMAL_COMPARE_RESULT_BASE_GREATER){
            btnSellWithCash.setEnabled(false);
        }else {
            btnSellWithCash.setEnabled(true);
        }
    }
}

