package ru.crystals.vaverjanov.dreamkas.view;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;

import com.octo.android.robospice.SpiceManager;
import com.octo.android.robospice.persistence.DurationInMillis;
import com.octo.android.robospice.persistence.exception.SpiceException;
import com.octo.android.robospice.request.listener.RequestListener;

import org.androidannotations.annotations.Bean;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EActivity;

import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.controller.GetGroupsRequest;
import ru.crystals.vaverjanov.dreamkas.controller.LighthouseSpiceService;
import ru.crystals.vaverjanov.dreamkas.controller.listeners.GetGroupsRequestListener;
import ru.crystals.vaverjanov.dreamkas.model.NamedObjects;

@EActivity(R.layout.activity_lighhouse_demo)
public class LighthouseDemoActivity extends Activity {

    //@ViewById
    //TextView lblToken;

    @Bean
    GetGroupsRequest groupsRequest;
    private SpiceManager spiceManager = new SpiceManager(LighthouseSpiceService.class);
    private final RequestListener<NamedObjects> groupsRequestListener = new GetGroupsRequestListener(this);
    private String token;

    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_lighhouse_demo);

        Intent intent = getIntent();
        token = intent.getStringExtra("access_token");
    }

    @Click(R.id.btnLoadGroups)
    void getGroups()
    {
        groupsRequest.setToken(token);
        spiceManager.execute(groupsRequest, null, DurationInMillis.NEVER, groupsRequestListener);
    }

    @Override
    public void onStart()
    {
        spiceManager.start(this);
        super.onStart();
    }

    @Override
    public void onStop()
    {
        spiceManager.shouldStop();
        super.onStop();
    }

    public void onGetGroupsSuccessRequest(NamedObjects result)
    {
        Log.d("AAA",result.toString());
    }

    public void onGetGroupsFailureRequest(SpiceException spiceException)
    {
        Log.d("AAA", spiceException.getMessage());
    }
}
