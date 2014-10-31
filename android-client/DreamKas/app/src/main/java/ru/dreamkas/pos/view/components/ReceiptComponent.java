package ru.dreamkas.pos.view.components;

import android.app.Activity;
import android.app.Dialog;
import android.content.Context;
import android.text.SpannableStringBuilder;
import android.util.AttributeSet;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import org.androidannotations.annotations.AfterViews;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EViewGroup;
import org.androidannotations.annotations.ItemClick;
import org.androidannotations.annotations.ViewById;

import ru.dreamkas.pos.DreamkasApp;
import ru.dreamkas.pos.R;
import ru.dreamkas.pos.adapters.ReceiptAdapter;
import ru.dreamkas.pos.model.Receipt;
import ru.dreamkas.pos.model.api.Product;
import ru.dreamkas.pos.view.misc.StringDecorator;
import ru.dreamkas.pos.view.popup.BasePopup;
import ru.dreamkas.pos.view.popup.ReceiptItemEditDialog;
import ru.dreamkas.pos.view.popup.ReceiptItemEditPopup;

@EViewGroup(R.layout.receipt_component)
public class ReceiptComponent extends LinearLayout {
    private final Context mContext;
    @ViewById
    ListView lvReceipt;

    @ViewById
    Button btnRegisterReceipt;

    @ViewById
    TextView lblReceiptEmpty;

    @ViewById
    LinearLayout llReceipt;

    private Receipt mReceipt;
    private ConfirmButtonComponent btnClearReceipt;
    private ReceiptAdapter mAdapter;

    public ReceiptComponent(Context context) {
        super(context);
        mContext = context;
    }

    public ReceiptComponent(Context context, AttributeSet attrs) {
        super(context, attrs);
        mContext = context;
    }

    public ReceiptComponent(Context context, AttributeSet attrs, int defStyle) {
        super(context, attrs, defStyle);
        mContext = context;
    }

    @AfterViews
    void receiptInit() {
        mReceipt = new Receipt();
        mAdapter = new ReceiptAdapter(mContext, R.layout.receipt_listview_item, mReceipt);
        lvReceipt.setAdapter(mAdapter);

        addFooterClearButton();

        btnClearReceipt.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {
                clearReceipt();
            }
        });
    }

    private void addFooterClearButton() {
        btnClearReceipt = (ConfirmButtonComponent)View.inflate(mContext,R.layout.clear_button, null);
        btnClearReceipt.setConfirmationText(DreamkasApp.getResourceString(R.string.msgClearReceiptConfitmationText));
        lvReceipt.addFooterView(btnClearReceipt);
    }

    public void clearReceipt(){
        mReceipt.clear();
        mAdapter.notifyDataSetChanged();
        changeReceiptTotal();
        setReceiptView(true);
    }

    @Click(R.id.btnRegisterReceipt)
    void registerReceipt(){
        //todo register
    }

    @ItemClick
    void lvReceiptItemClicked(Product obj) {
        /*View view = LayoutInflater.from(getContext()).inflate(R.layout.edit_receipt_item, null, false);
        ReceiptItemEditPopup aa = new ReceiptItemEditPopup(getContext(), view, null);
        aa.show(this);*/

        /*View view = LayoutInflater.from(mContext).inflate(R.layout.edit_receipt_item, (ViewGroup)getRootView(), false);
        ReceiptItemEditPopup aa = new ReceiptItemEditPopup(mContext, view, null);
        aa.show(this);*/

        /*final Dialog dialog = new Dialog(getContext());

        dialog.getWindow().setBackgroundDrawableResource(android.R.color.transparent);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.setContentView(R.layout.edit_receipt_item);

        WindowManager.LayoutParams lp = new WindowManager.LayoutParams();
        lp.copyFrom(dialog.getWindow().getAttributes());
        lp.width = 800;
        lp.height = WindowManager.LayoutParams.MATCH_PARENT;

        dialog.show();
        dialog.getWindow().setAttributes(lp);*/

        final ReceiptItemEditDialog dialog = new ReceiptItemEditDialog(getContext());
        dialog.show();



    }

    private void scrollToBottom() {
        lvReceipt.post(new Runnable() {
            @Override
            public void run() {
                lvReceipt.setSelection(mReceipt.size() - 1);
            }
        });
    }

    private void changeReceiptTotal() {
        SpannableStringBuilder msgSellInTheAmountOff = StringDecorator.buildStringWithRubleSymbol(DreamkasApp.getResourceString(R.string.msgSellInTheAmountOff),
                                                                                                                    mReceipt.getTotal(), StringDecorator.RUBLE_CODE);
        btnRegisterReceipt.setText(msgSellInTheAmountOff);
    }

    public void add(Product product) {
        setReceiptView(false);
        mReceipt.add(product);
        mAdapter.notifyDataSetChanged();

        changeReceiptTotal();
        btnClearReceipt.changeState(ConfirmButtonComponent.State.REGULAR);

        scrollToBottom();
    }

    private void setReceiptView(boolean isEmpty) {
        if(isEmpty){
            lblReceiptEmpty.setVisibility(VISIBLE);
            llReceipt.setVisibility(GONE);
        }else {
            lblReceiptEmpty.setVisibility(GONE);
            llReceipt.setVisibility(VISIBLE);
        }
    }
}
