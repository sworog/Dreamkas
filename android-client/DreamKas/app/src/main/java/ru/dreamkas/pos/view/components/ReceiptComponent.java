package ru.dreamkas.pos.view.components;

import android.app.Activity;
import android.content.Context;
import android.content.DialogInterface;
import android.text.SpannableStringBuilder;
import android.util.AttributeSet;
import android.view.View;
import android.widget.Button;
import android.widget.HeaderViewListAdapter;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;
import org.androidannotations.annotations.AfterViews;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EViewGroup;
import org.androidannotations.annotations.ItemClick;
import org.androidannotations.annotations.ViewById;
import java.math.BigDecimal;
import ru.dreamkas.pos.DreamkasApp;
import ru.dreamkas.pos.R;
import ru.dreamkas.pos.adapters.ReceiptAdapter;
import ru.dreamkas.pos.model.Receipt;
import ru.dreamkas.pos.model.ReceiptItem;
import ru.dreamkas.pos.model.api.Product;
import ru.dreamkas.pos.view.misc.StringDecorator;
import ru.dreamkas.pos.view.popup.ReceiptItemEditDialog;

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
    private boolean mDialogInProgress;

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
        btnClearReceipt.setTouchOwner((Activity)mContext);
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
    void lvReceiptItemClicked(ReceiptItem item) {
        final ReceiptItemEditDialog dialog = new ReceiptItemEditDialog(getContext());

        dialog.setReceiptItem(item);
        dialog.show();
        dialog.setOnDismissListener(new DialogInterface.OnDismissListener() {
            @Override
            public void onDismiss(final DialogInterface arg0) {
                switch (dialog.getResult()){
                    case Save:
                        ReceiptItem item = dialog.getReceiptItem();

                        int start = lvReceipt.getFirstVisiblePosition();
                        for(int i=start, j=lvReceipt.getLastVisiblePosition();i<=j;i++)
                            if(item==lvReceipt.getItemAtPosition(i)){
                                View view = lvReceipt.getChildAt(i-start);
                                lvReceipt.getAdapter().getView(i, view, lvReceipt);
                                break;
                            }

                        BigDecimal delta = item.getTotal().add(dialog.getBackup().getTotal().negate());
                        mReceipt.changeTo(delta);
                        changeReceiptTotal();
                        break;
                    case Cancel:
                        break;
                    case RemoveReceipt:
                        //((ReceiptAdapter) ((HeaderViewListAdapter)lvReceipt.getAdapter()).getWrappedAdapter()).remove(dialog.getReceiptItem());
                        mAdapter.remove(dialog.getReceiptItem());
                        mAdapter.notifyDataSetChanged();
                        if(mAdapter.getCount()==0){
                            setReceiptView(true);
                        }
                        changeReceiptTotal();

                        break;
                }
            }
        });
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
        SpannableStringBuilder msgSellInTheAmountOff = StringDecorator.buildStringWithRubleSymbol(DreamkasApp.getResourceString(R.string.msgSellInTheAmountOff), DreamkasApp.getMoneyFormat().format(mReceipt.getTotal()), StringDecorator.RUBLE_CODE);
        btnRegisterReceipt.setText(msgSellInTheAmountOff);
    }

    public void add(Product product) {
        ReceiptItem item = prepareProductForReceipt(product);
        if(item != null){
            addReceiptItem(item);
        }
    }

    private ReceiptItem prepareProductForReceipt(Product product) {
        if(product.getSellingPrice() != null && product.getSellingPrice() != BigDecimal.ZERO){
            return new ReceiptItem(product);
        }

        if(mDialogInProgress){
            return null;
        }

        mDialogInProgress = true;

        final ReceiptItem receiptItem = new ReceiptItem(product);

        final ReceiptItemEditDialog dialog = new ReceiptItemEditDialog(getContext());

        dialog.setReceiptItem(receiptItem);
        dialog.setDeleteButtonVisible(View.GONE);
        dialog.show();
        dialog.setOnDismissListener(new DialogInterface.OnDismissListener() {
            @Override
            public void onDismiss(final DialogInterface arg0) {
                mDialogInProgress = false;
                switch (dialog.getResult()){
                    case Save:
                        addReceiptItem(receiptItem);
                        break;
                    case Cancel:
                    case RemoveReceipt:
                        break;
                }
            }
        });

        return null;
    }

    private void addReceiptItem(ReceiptItem item){
        setReceiptView(false);
        mReceipt.add(item);
        mAdapter.notifyDataSetChanged();

        BigDecimal delta = item.getTotal();
        mReceipt.changeTo(delta);
        changeReceiptTotal();

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
