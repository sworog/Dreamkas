package ru.crystals.vaverjanov.dreamkas.view;

import android.app.Activity;
import android.app.Fragment;
import android.app.ProgressDialog;

public class BaseFragment extends Fragment
{
    protected IChangeFragmentHandler changeFragmentCallback;
    public ProgressDialog progressDialog;

    @Override
    public void onAttach(Activity activity)
    {
        super.onAttach(activity);

        try {
            changeFragmentCallback = (IChangeFragmentHandler) activity;
        } catch (ClassCastException e) {
            throw new ClassCastException(activity.toString()
                    + " must implement IChangeFragmentHandler");
        }
    }

    protected void showProgressDialog(String msg)
    {
        progressDialog = new ProgressDialog(getActivity());
        progressDialog.setMessage(msg);
        progressDialog.setIndeterminate(true);
        progressDialog.setCancelable(true);
        progressDialog.show();
    }
}