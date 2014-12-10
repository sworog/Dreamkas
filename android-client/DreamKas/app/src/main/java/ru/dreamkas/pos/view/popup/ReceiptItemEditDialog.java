package ru.dreamkas.pos.view.popup;

import android.app.Dialog;
import android.content.Context;
import android.text.Editable;
import android.text.SpannableStringBuilder;
import android.text.TextWatcher;
import android.view.MotionEvent;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.TextView;
import org.springframework.util.ObjectUtils;
import java.math.BigDecimal;
import java.text.DecimalFormat;
import java.text.DecimalFormatSymbols;
import java.util.Locale;

import ru.dreamkas.pos.Constants;
import ru.dreamkas.pos.DreamkasApp;
import ru.dreamkas.pos.R;
import ru.dreamkas.pos.model.listeners.ValueChangedListener;
import ru.dreamkas.pos.model.ReceiptItem;
import ru.dreamkas.pos.view.components.ConfirmButtonComponent;
import ru.dreamkas.pos.view.components.NumericEditText;
import ru.dreamkas.pos.view.components.NumericUpDown;
import ru.dreamkas.pos.view.misc.StringDecorator;

public class ReceiptItemEditDialog extends Dialog {

    private TextView lblTotal;
    private ReceiptItem mBackup;
    private DialogResult result = DialogResult.Cancel;
    private ConfirmButtonComponent btnRemoveFromReceipt;
    private ImageButton btnClose;
    private ReceiptItem mReceiptItem;
    private TextView lblProductName;
    private NumericEditText txtSellingPrice;
    private NumericUpDown nupQuantity;
    private Button btnSave;
    private int mDeleteButtonVisibility = View.VISIBLE;

    public enum DialogResult{RemoveReceipt, Save, Cancel;}

    public ReceiptItemEditDialog(Context context) {
        super(context, R.style.dialog_slide_anim);
    }

    @Override
    public void show(){
        getWindow().setBackgroundDrawableResource(android.R.color.transparent);
        requestWindowFeature(Window.FEATURE_NO_TITLE);
        setContentView(R.layout.edit_receipt_item);
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
        mReceiptItem.setSellingPrice(mBackup.getSellingPrice());
        mReceiptItem.setQuantity(mBackup.getQuantity());

        result = DialogResult.Cancel;
        super.cancel();
    }

    public void setDeleteButtonVisible(int visibility) {
        mDeleteButtonVisibility = visibility;
    }

    public ReceiptItem getBackup() {
        return mBackup;
    }

    private void init() {




        btnSave = (Button) findViewById(R.id.btnSave);
        btnSave.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                save();
            }
        });

        lblTotal = (TextView) findViewById(R.id.lblTotal);
        calcTotal();

        btnRemoveFromReceipt = (ConfirmButtonComponent) findViewById(R.id.btnRemoveFromReceipt);
        btnRemoveFromReceipt.setVisibility(mDeleteButtonVisibility);
        btnRemoveFromReceipt.setTouchOwner(this);
        btnRemoveFromReceipt.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                removeReceipt();
            }
        });

        btnClose = (ImageButton) findViewById(R.id.btnCloseModal);
        btnClose.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                cancel();
            }
        });
        lblProductName = (TextView) findViewById(R.id.lblProductName);
        lblProductName.setText(mReceiptItem.getProduct().getName());

        txtSellingPrice = (NumericEditText) findViewById(R.id.txtSellingPrice);
        txtSellingPrice.setValue(mReceiptItem.getSellingPrice());

        nupQuantity = (NumericUpDown) findViewById(R.id.nupQuantity);
        nupQuantity.setValueChangedListener(new ValueChangedListener<BigDecimal>() {
            @Override
            public void changedTo(BigDecimal newValue) {
                mReceiptItem.setQuantity(newValue);
                calcTotal();
                validate();
            }
        });
        nupQuantity.setValue(mReceiptItem.getQuantity());

        addSellingPriceChangeListeners();

        validate();
    }

    private void calcTotal() {
        SpannableStringBuilder total = StringDecorator.buildStringWithRubleSymbol(DreamkasApp.getResourceString(R.string.msg_info_ruble_value), DreamkasApp.getMoneyFormat().format(mReceiptItem.getTotal()), StringDecorator.RUBLE_CODE);
        lblTotal.setText(total);
    }

    public void setReceiptItem(ReceiptItem item) {
        this.mBackup = new ReceiptItem(item);
        this.mReceiptItem = item;
    }

    public ReceiptItem getReceiptItem() {
        return mReceiptItem;
    }

    private void addSellingPriceChangeListeners(){
        txtSellingPrice.addTextChangedListener(new TextWatcher(){
            public void afterTextChanged(Editable s){
                validate();
            }
            public void beforeTextChanged(CharSequence s, int start, int count, int after){}
            public void onTextChanged(CharSequence s, int start, int before, int count){}
        });
    }

    public void save(){
        result = DialogResult.Save;
        dismiss();
    }

    public void removeReceipt(){
        result = DialogResult.RemoveReceipt;
        dismiss();
    }

    public DialogResult getResult(){
        return result;
    }

    private void validate() {
        mReceiptItem.setSellingPrice(BigDecimal.ZERO);

        if (txtSellingPrice.length() > 0){
            txtSellingPrice.setError(null);
            try{
                BigDecimal value = txtSellingPrice.getValue();
                if(value.signum() < 0){
                    txtSellingPrice.setError(DreamkasApp.getResourceString(R.string.msg_error_negative_value));
                }else if(value.signum() == 0){
                    txtSellingPrice.setError(DreamkasApp.getResourceString(R.string.msg_error_zero_value));
                }else {
                    mReceiptItem.setSellingPrice(value);
                }
            }catch (Exception ex){
                txtSellingPrice.setError(DreamkasApp.getResourceString(R.string.msg_error_wrong_format));
            }finally {
                calcTotal();
            }
        }else if(txtSellingPrice.length() == 0){
            txtSellingPrice.setError(DreamkasApp.getResourceString(R.string.msg_error_empty_value));
        }

        if(txtSellingPrice.getError() == null && nupQuantity.getError() == null){
            btnSave.setEnabled(true);
        }else {
            btnSave.setEnabled(false);
        }
    }
}




