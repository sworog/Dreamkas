package ru.dreamkas.pos.view.popup;

import android.app.Dialog;
import android.app.DialogFragment;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.os.Bundle;
import android.view.Window;
import android.view.WindowManager;

import ru.dreamkas.pos.R;

public class BaseDialog extends DialogFragment {
    public enum DialogResult{OK, CANCEL;}

    private DialogResult mResult = DialogResult.CANCEL;
    public ProgressDialog progressDialog;
    private DialogInterface.OnDismissListener mDismissListener;

    public void setOnDismissListener(DialogInterface.OnDismissListener dismissListener) {
        mDismissListener = dismissListener;
    }

    public BaseDialog() {
        super();
    }

    @Override
    public Dialog onCreateDialog(Bundle bundle){
        Dialog dialog = super.onCreateDialog(bundle);

        dialog.getWindow().getAttributes().windowAnimations = R.style.dialog_animation;

        dialog.getWindow().setBackgroundDrawableResource(android.R.color.transparent);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.setContentView(R.layout.login_dialog);
        dialog.setCanceledOnTouchOutside(false);

        WindowManager.LayoutParams lp = new WindowManager.LayoutParams();
        lp.copyFrom(dialog.getWindow().getAttributes());
        lp.width = 800;
        lp.height = WindowManager.LayoutParams.MATCH_PARENT;
        dialog.getWindow().setLayout(lp.width, lp.height);
        return dialog;
    }

    @Override
    public void onDestroy(){
        super.onDestroy();
        if(mDismissListener!= null){
            mDismissListener.onDismiss(null);
        }
    }

    public void cancel(){
        setResult(DialogResult.CANCEL);
        getDialog().cancel();
    }

    public void done() {
        setResult(DialogResult.OK);
        dismiss();
    }

    public DialogResult getResult(){
        return mResult;
    }

    protected void setResult(DialogResult result) {
        mResult = result;
    }

    protected void showProgressDialog(String msg){
        progressDialog = new ProgressDialog(getActivity());
        progressDialog.setMessage(msg);
        progressDialog.setIndeterminate(true);
        progressDialog.setCancelable(true);
        progressDialog.show();
    }

    protected void stopProgressDialog(){
        progressDialog.dismiss();
    }
}

