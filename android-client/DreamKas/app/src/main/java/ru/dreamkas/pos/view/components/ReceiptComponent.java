package ru.dreamkas.pos.view.components;

import android.app.Activity;
import android.content.Context;
import android.content.DialogInterface;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.text.SpannableStringBuilder;
import android.util.AttributeSet;
import android.util.TypedValue;
import android.view.MenuItem;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.TranslateAnimation;
import android.widget.AbsListView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;


import ru.dreamkas.pos.Constants;
import ru.dreamkas.pos.controller.PreferencesManager;
import ru.dreamkas.pos.view.components.regular.ButtonRectangleExt;

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
import ru.dreamkas.pos.view.popup.PaymentDialog;
import ru.dreamkas.pos.view.popup.PaymentDialog_;
import ru.dreamkas.pos.view.popup.ReceiptItemEditDialog;

@EViewGroup(R.layout.receipt_component)
public class ReceiptComponent extends LinearLayout {
    private final Context mContext;
    @ViewById
    ListView lvReceipt;

    @ViewById
    ButtonRectangleExt btnRegisterReceipt;

    @ViewById
    TextView lblReceiptEmpty;

    @ViewById
    LinearLayout llReceipt;

    @Click(R.id.btnSlide)
    void slide(){
        Animation animation = new TranslateAnimation(0, 100, 0, 0);
        animation.setDuration(1000);
        this.startAnimation(animation);
    }

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

        //AlphaInAnimationAdapter animationAdapter = new AlphaInAnimationAdapter(mAdapter);
        //animationAdapter.setAbsListView(lvReceipt);
        //lvReceipt.setAdapter(animationAdapter);

        lvReceipt.setAdapter(mAdapter);

        addFooterClearButton();

        btnRegisterReceipt.setRippleSpeed(30);

        btnClearReceipt.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {
                clearReceipt();
            }
        });







        Toolbar toolbar = (Toolbar) findViewById(R.id.tbReceipt);
        toolbar.setOnMenuItemClickListener(new Toolbar.OnMenuItemClickListener() {
            @Override
            public boolean onMenuItemClick(MenuItem item) {
                return true;
            }
        });

        toolbar.inflateMenu(R.menu.receipt_menu);
    }

    private void addFooterClearButton() {
        btnClearReceipt = (ConfirmButtonComponent)View.inflate(mContext,R.layout.clear_button, null);

        int height = (int) TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_DIP, 48, getResources().getDisplayMetrics());
        AbsListView.LayoutParams params = new AbsListView.LayoutParams(LayoutParams.MATCH_PARENT,height);
        //params.topMargin = 15;

        btnClearReceipt.setLayoutParams(params);

        if(!this.isInEditMode()){
            btnClearReceipt.setConfirmationText(DreamkasApp.getResourceString(R.string.msgClearReceiptConfitmationText));
            btnClearReceipt.setTouchOwner((Activity) mContext);
        }


        lvReceipt.addFooterView(btnClearReceipt);

        btnClearReceipt.setRippleSpeed(30);
    }

    public void clearReceipt(){
        mReceipt.clear();
        mAdapter.notifyDataSetChanged();
        changeReceiptTotal();
        setReceiptView(true);
    }

    @Click(R.id.btnRegisterReceipt)
    void registerReceipt(){
        if(mDialogInProgress){
            return ;
        }
        mDialogInProgress = true;

        final PaymentDialog_ dialog = new PaymentDialog_();

        Bundle bundle = new Bundle();
        bundle.putString(Constants.ACCESS_TOKEN, PreferencesManager.getInstance().getToken());
        dialog.setArguments(bundle);

        dialog.setReceipt(mReceipt);
        dialog.show(((Activity)mContext).getFragmentManager(), "payment_dialog");

        dialog.setOnDismissListener(new DialogInterface.OnDismissListener() {
            @Override
            public void onDismiss(final DialogInterface arg0) {
                mDialogInProgress = false;
                switch (dialog.getResult()){
                    case OK:
                        clearReceipt();
                        break;
                    case CANCEL:
                        break;
                }
            }
        });
    }

    @ItemClick
    void lvReceiptItemClicked(ReceiptItem item) {
        if(mDialogInProgress){
            return ;
        }
        mDialogInProgress = true;
        final ReceiptItemEditDialog dialog = new ReceiptItemEditDialog(getContext());

        dialog.setReceiptItem(item);
        dialog.show();
        dialog.setOnDismissListener(new DialogInterface.OnDismissListener() {
            @Override
            public void onDismiss(final DialogInterface arg0) {
                mDialogInProgress = false;
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
       /* postDelayed(new Runnable() {
            public void run() {
                lvReceipt.setSelection(mReceipt.size() - 1);
            }
        }, 200L);*/

        lvReceipt.post(new Runnable() {
            @Override
            public void run() {
                lvReceipt.setSelection(mReceipt.size() - 1);
            }
        });
    }

    private void changeReceiptTotal() {
        SpannableStringBuilder msgSellInTheAmountOff = StringDecorator.buildStringWithRubleSymbol(DreamkasApp.getResourceString(R.string.msgSellInTheAmountOff), DreamkasApp.getMoneyFormat().format(mReceipt.getTotal()), StringDecorator.RUBLE_CODE);
        btnRegisterReceipt.getTextView().setText(msgSellInTheAmountOff);
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

        Animation animation = new TranslateAnimation(0, 0, -48, 0);
        animation.setDuration(200);

        btnClearReceipt.startAnimation(animation);


        mAdapter.notifyDataSetChanged();

        mReceipt.add(item);

        mAdapter.notifyDataSetChanged();

        if(lvReceipt.getChildCount() > 1){
            lvReceipt.getChildAt(lvReceipt.getChildCount()-2).bringToFront();
        }

        BigDecimal delta = item.getTotal();
        mReceipt.changeTo(delta);
        changeReceiptTotal();

        changeReceiptTotal();
        btnClearReceipt.changeState(ConfirmButtonComponent.State.REGULAR);


       // scrollToBottom();


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
