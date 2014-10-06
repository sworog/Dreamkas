package ru.dreamkas.pos.view.fragments;

import android.app.Activity;
import android.os.Bundle;

import com.octo.android.robospice.SpiceManager;

import ru.dreamkas.pos.Constants;
import ru.dreamkas.pos.controller.DreamkasSpiceService;

public class AuthRequestsContainingFragment extends BaseFragment{

    private String mToken;

    private SpiceManager mSpiceManager;


    @Override
    public void onCreate(Bundle bundle){
        super.onCreate(bundle);
        mToken = getArguments().getString(Constants.ACCESS_TOKEN);
        //mToken = PreferencesManager.getInstance().getToken();
    }

    @Override
    public void onAttach(Activity activity){
        super.onAttach(activity);
        mSpiceManager = new SpiceManager(DreamkasSpiceService.class);
        mSpiceManager.start(activity);
    }

    @Override
    public void onStop(){
        mSpiceManager.shouldStop();
        super.onStop();
    }

    protected String getToken(){
        return mToken;
    }

    protected SpiceManager getSpiceManager(){
        return mSpiceManager;
    }
}
