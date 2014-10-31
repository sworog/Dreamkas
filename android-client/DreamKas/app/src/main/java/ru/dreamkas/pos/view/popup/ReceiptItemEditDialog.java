package ru.dreamkas.pos.view.popup;

import android.app.Dialog;
import android.content.Context;
import android.view.MotionEvent;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.view.components.ConfirmButtonComponent;

public class ReceiptItemEditDialog extends Dialog {
    private ConfirmButtonComponent btnRemoveFromReceipt;

    public ReceiptItemEditDialog(Context context) {
        super(context);
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

        init();
    }

    private void init() {
        btnRemoveFromReceipt = (ConfirmButtonComponent) findViewById(R.id.btnRemoveFromReceipt);
        btnRemoveFromReceipt.setTouchOwner(this);
        //btnRemoveFromReceipt.setContainer(findViewById(R.id.llFragmentContainer));
        btnRemoveFromReceipt.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dismiss();
            }
        });
    }

    @Override
    public boolean onTouchEvent(MotionEvent event) {

        return false;
    }

    @Override
    public boolean dispatchTouchEvent(MotionEvent ev) {
        return super.dispatchTouchEvent(ev);
    }






    /*public ReceiptItemEditDialog(Context ctx, View inflate) {
        super(ctx, inflate, listener);




        //btnRemoveFromReceipt.setContainer((RelativeLayout)((Activity)ctx).findViewById(android.R.id.content));
        //btnRemoveFromReceipt.setContainer(((Activity)ctx).getWindow().getDecorView().findViewById(android.R.id.content));
        //btnRemoveFromReceipt.setContainer(getContentView().getRootView());
        btnRemoveFromReceipt.setContainer(getContentView().findViewById(R.id.llFragmentContainer));
        btnRemoveFromReceipt.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dismiss();
            }
        });
    }*/


}

