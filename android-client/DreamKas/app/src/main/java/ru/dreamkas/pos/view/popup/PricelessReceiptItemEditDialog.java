package ru.dreamkas.pos.view.popup;

import android.app.Dialog;
import android.content.Context;
import android.text.SpannableStringBuilder;
import android.view.MotionEvent;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.ImageButton;
import android.widget.TextView;

import org.apache.commons.lang3.StringUtils;

import java.math.BigDecimal;
import java.text.DecimalFormat;
import java.text.DecimalFormatSymbols;
import java.text.ParseException;

import ru.dreamkas.pos.Constants;
import ru.dreamkas.pos.DreamkasApp;
import ru.dreamkas.pos.R;
import ru.dreamkas.pos.model.ReceiptItem;
import ru.dreamkas.pos.model.listeners.ValueChangedListener;
import ru.dreamkas.pos.view.components.ConfirmButtonComponent;
import ru.dreamkas.pos.view.components.NumericUpDown;
import ru.dreamkas.pos.view.components.regular.ButtonFlatExt;
import ru.dreamkas.pos.view.components.regular.ButtonRectangleExt;
import ru.dreamkas.pos.view.components.regular.TextViewTypefaced;
import ru.dreamkas.pos.view.misc.StringDecorator;

public class PricelessReceiptItemEditDialog extends Dialog {

    private ReceiptItem mBackup;

    private DialogResult result = DialogResult.Cancel;


    private ImageButton btnClose;
    private ReceiptItem mReceiptItem;
    private TextViewTypefaced lblProductName;
    private TextViewTypefaced lblSellingPrice;

    private ButtonRectangleExt btnSave;

    private ButtonFlatExt btn1;
    private ButtonFlatExt btn2;
    private ButtonFlatExt btn3;
    private ButtonFlatExt btn4;
    private ButtonFlatExt btn5;
    private ButtonFlatExt btn6;
    private ButtonFlatExt btn7;
    private ButtonFlatExt btn8;
    private ButtonFlatExt btn9;
    private ButtonFlatExt btn0;
    private ButtonFlatExt btnComma;
    private ButtonFlatExt btnBack;

    private String mCurrentValue = "";

    public enum DialogResult{Save, Cancel;}

    public PricelessReceiptItemEditDialog(Context context) {
        super(context, R.style.dialog_slide_anim);
    }

    @Override
    public void show(){
        getWindow().setBackgroundDrawableResource(android.R.color.transparent);
        requestWindowFeature(Window.FEATURE_NO_TITLE);
        setContentView(R.layout.priceless_receipt_item);
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

    public ReceiptItem getBackup() {
        return mBackup;
    }

    private void init() {
        btnSave = (ButtonRectangleExt) findViewById(R.id.btnSave);
        btnSave.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                save();
            }
        });

        btnClose = (ImageButton) findViewById(R.id.btnCloseModal);
        btnClose.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                cancel();
            }
        });

        lblProductName = (TextViewTypefaced) findViewById(R.id.lblProductName);
        lblProductName.setText(mReceiptItem.getProduct().getName());

        lblSellingPrice = (TextViewTypefaced) findViewById(R.id.lblSellingPrice);

        int rippleSpeed = 40;

        btn1 = (ButtonFlatExt) findViewById(R.id.btn1);
        btn1.setRippleSpeed(rippleSpeed);
        btn1.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                addSymbol("1");
            }
        });

        btn2 = (ButtonFlatExt) findViewById(R.id.btn2);
        btn2.setRippleSpeed(rippleSpeed);
        btn2.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                addSymbol("2");
            }
        });

        btn3 = (ButtonFlatExt) findViewById(R.id.btn3);
        btn3.setRippleSpeed(rippleSpeed);
        btn3.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                addSymbol("3");
            }
        });

        btn4 = (ButtonFlatExt) findViewById(R.id.btn4);
        btn4.setRippleSpeed(rippleSpeed);
        btn4.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                addSymbol("4");
            }
        });

        btn5 = (ButtonFlatExt) findViewById(R.id.btn5);
        btn5.setRippleSpeed(rippleSpeed);
        btn5.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                addSymbol("5");
            }
        });

        btn6 = (ButtonFlatExt) findViewById(R.id.btn6);
        btn6.setRippleSpeed(rippleSpeed);
        btn6.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                addSymbol("6");
            }
        });

        btn7 = (ButtonFlatExt) findViewById(R.id.btn7);
        btn7.setRippleSpeed(rippleSpeed);
        btn7.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                addSymbol("7");
            }
        });

        btn8 = (ButtonFlatExt) findViewById(R.id.btn8);
        btn8.setRippleSpeed(rippleSpeed);
        btn8.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                addSymbol("8");
            }
        });

        btn9 = (ButtonFlatExt) findViewById(R.id.btn9);
        btn9.setRippleSpeed(rippleSpeed);
        btn9.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                addSymbol("9");
            }
        });

        btn0 = (ButtonFlatExt) findViewById(R.id.btn0);
        btn0.setRippleSpeed(rippleSpeed);
        btn0.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                addSymbol("0");
            }
        });

        btnComma = (ButtonFlatExt) findViewById(R.id.btnComma);
        btnComma.setRippleSpeed(rippleSpeed);
        btnComma.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                addSymbol(",");
            }
        });
        btnBack = (ButtonFlatExt) findViewById(R.id.btnBack);
        btnBack.setRippleSpeed(rippleSpeed);
        btnBack.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                addSymbol(null);
            }
        });

        validate();
    }

    private void addSymbol(String symbol) {
        if(mCurrentValue.length() > 20){
            return;
        }

        if (symbol != null){
            if(symbol.equals("0")){
                if(mCurrentValue.length() == 1 && mCurrentValue.contains("0")){
                    return;
                }
            }

            if(symbol.equals(",")){
                if(mCurrentValue.length() == 0){
                    return;
                }
                if(mCurrentValue.contains(",")){
                    return;
                }
            }

            String[] valueParts = mCurrentValue.split(",");

            if(valueParts.length > 1){
                String lastBlock = valueParts[valueParts.length - 1];
                if(lastBlock.length() >= 2){
                    return;
                }
            }

            mCurrentValue += symbol;
        }else {
            mCurrentValue =  StringUtils.chop(mCurrentValue);
        }

        if(mCurrentValue.length() == 0){
            lblSellingPrice.setText("Укажите цену");
        }else {
            SpannableStringBuilder sellingPrice = StringDecorator.buildStringWithRubleSymbol(30, mCurrentValue + "%c", StringDecorator.RUBLE_CODE);
            lblSellingPrice.setText(sellingPrice);
        }
        validate();
    }

    public void setReceiptItem(ReceiptItem item) {
        this.mBackup = new ReceiptItem(item);
        this.mReceiptItem = item;
    }

    public ReceiptItem getReceiptItem() {
        return mReceiptItem;
    }

    public void save(){

        mReceiptItem.setSellingPrice(converValueToBigDecimal());
        result = DialogResult.Save;
        dismiss();
    }

    private BigDecimal converValueToBigDecimal() {
        DecimalFormatSymbols otherSymbols = new DecimalFormatSymbols();
        otherSymbols.setDecimalSeparator(',');
        DecimalFormat format = new DecimalFormat("0", otherSymbols);
        format.setParseBigDecimal(true);
        format.setMinimumFractionDigits(2);
        format.setMaximumFractionDigits(2);

        BigDecimal bd = BigDecimal.ZERO;
        try {
            bd = (BigDecimal)format.parse(mCurrentValue);
        } catch (ParseException e) {
            e.printStackTrace();
        }

        bd.setScale(2, BigDecimal.ROUND_HALF_UP);

        return bd;
    }

    public DialogResult getResult(){
        return result;
    }

    private void validate() {
        mReceiptItem.setSellingPrice(BigDecimal.ZERO);

        if(mCurrentValue.length() != 0 && converValueToBigDecimal().compareTo(BigDecimal.ZERO) != Constants.DECIMAL_COMPARE_RESULT_BASE_EQUAL){
            btnSave.setEnabled(true);
        }else {
            btnSave.setEnabled(false);
        }
    }
}




