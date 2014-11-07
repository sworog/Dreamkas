package ru.dreamkas.pos.controller.requests;

import com.octo.android.robospice.SpiceManager;
import com.octo.android.robospice.persistence.DurationInMillis;
import org.androidannotations.annotations.EBean;
import org.androidannotations.annotations.rest.RestService;

import ru.dreamkas.pos.controller.Command;
import ru.dreamkas.pos.controller.DreamkasRestClient;
import ru.dreamkas.pos.controller.listeners.request.GetStoresRequestListener;

@EBean
public class AuthorisedRequestWrapper{
    private BaseRequest mRequest;

    @RestService
    protected DreamkasRestClient restClient;

    private SpiceManager mSpiceManager;

    public void init(SpiceManager spiceManager, BaseRequest request,String accessToken){
        mSpiceManager = spiceManager;
        mRequest = request;
        mRequest.init(restClient);
        restClient.setHeader("Authorization", "Bearer " + accessToken);
    }

    public void execute(Command SuccessFinishCommand, Command<com.octo.android.robospice.persistence.exception.SpiceException> FailureFinishCommand) {
        GetStoresRequestListener listener = new GetStoresRequestListener(SuccessFinishCommand, FailureFinishCommand);
        mSpiceManager.execute(mRequest, null, DurationInMillis.NEVER, listener);
        listener.requestStarted();
    }
}
