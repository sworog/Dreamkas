package ru.dreamkas.pos.view.popup;

import android.app.Activity;
import android.widget.Toast;

import com.octo.android.robospice.SpiceManager;
import com.octo.android.robospice.exception.RequestCancelledException;
import com.octo.android.robospice.persistence.exception.SpiceException;

import org.springframework.http.HttpStatus;
import org.springframework.web.client.HttpClientErrorException;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.controller.DreamkasSpiceService;

public class RequestContainingDialog extends BaseDialog {
    private SpiceManager mSpiceManager;

    public RequestContainingDialog() {
        super();
    }

    @Override
    public void onAttach(Activity activity){
        super.onAttach(activity);
        mSpiceManager = new SpiceManager(DreamkasSpiceService.class);
        mSpiceManager.start(activity);
    }

    @Override
    public void onDestroy(){
        if(mSpiceManager.isStarted()){
            mSpiceManager.shouldStop();
        }
        //super.onStop();
        super.onDestroy();
    }

    protected SpiceManager getSpiceManager(){
        return mSpiceManager;
    }

    protected void showRequestErrorToast(SpiceException spiceException){
        String msg;
        if(spiceException.getCause() instanceof HttpClientErrorException){
            HttpClientErrorException exception = (HttpClientErrorException)spiceException.getCause();
            if(exception.getStatusCode().equals(HttpStatus.UNAUTHORIZED)){
                //wrong credentials
                msg = getResources().getString(R.string.error_unauthorized);
            }
            else{
                //other Network exception
                msg = spiceException.getMessage();
            }
        }
        else if(spiceException instanceof RequestCancelledException){
            //cancelled
            msg = spiceException.getMessage();
        }
        else{
            //other exception
            msg = spiceException.getMessage();
        }

        Toast.makeText(getActivity(), msg, Toast.LENGTH_LONG).show();
    }
}

