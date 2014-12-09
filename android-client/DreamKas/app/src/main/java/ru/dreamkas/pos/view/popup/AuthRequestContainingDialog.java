package ru.dreamkas.pos.view.popup;

import android.os.Bundle;
import ru.dreamkas.pos.Constants;

public class AuthRequestContainingDialog extends RequestContainingDialog {
    private String mToken;

    public AuthRequestContainingDialog() {
        super();
    }

    @Override
    public void onCreate(Bundle bundle){
        super.onCreate(bundle);
        mToken = getArguments().getString(Constants.ACCESS_TOKEN);
        //mToken = PreferencesManager.getInstance().getToken();
    }

    protected String getToken(){
        return mToken;
    }
}

