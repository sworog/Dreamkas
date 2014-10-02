package ru.crystals.vaverjanov.dreamkas.controller.requests;

import com.octo.android.robospice.SpiceManager;
import com.octo.android.robospice.persistence.DurationInMillis;
import org.androidannotations.annotations.EBean;
import org.androidannotations.annotations.rest.RestService;

import ru.crystals.vaverjanov.dreamkas.controller.Command;
import ru.crystals.vaverjanov.dreamkas.controller.LighthouseRestClient;
import ru.crystals.vaverjanov.dreamkas.controller.listeners.request.GetStoresRequestListener;

@EBean
public class AuthorisedRequestWrapper
{
    private BaseRequest mRequest;

    @RestService
    protected LighthouseRestClient restClient;

    private SpiceManager mSpiceManager;

    public void init(SpiceManager spiceManager, BaseRequest request,String accessToken){
        mSpiceManager = spiceManager;
        mRequest = request;
        mRequest.init(restClient);
        restClient.setHeader("Authorization", "Bearer " + accessToken);
    }

    public void execute(Command SuccessFinishCommand, Command FailureFinishCommand) {
        GetStoresRequestListener listener = new GetStoresRequestListener(SuccessFinishCommand, FailureFinishCommand);
        mSpiceManager.execute(mRequest, null, DurationInMillis.NEVER, listener);
        listener.requestStarted();
    }
}
