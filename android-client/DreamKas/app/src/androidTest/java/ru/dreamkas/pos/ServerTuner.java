package ru.dreamkas.pos;

import android.util.Pair;

import com.surftools.BeanstalkClient.Client;
import com.surftools.BeanstalkClient.Job;
import com.surftools.BeanstalkClientImpl.ClientImpl;

import org.apache.commons.lang3.StringUtils;
import org.json.JSONException;

import java.util.ArrayList;
import java.util.UUID;

import ru.dreamkas.pos.model.api.Product;
import ru.dreamkas.pos.remoteCommand.CreateUserRequest;
import ru.dreamkas.pos.remoteCommand.HelpRequest;
import ru.dreamkas.pos.remoteCommand.RebuildDatabaseRequest;
import ru.dreamkas.pos.remoteCommand.RemoteCommandProcessor;
import ru.dreamkas.pos.remoteCommand.Status;

public class ServerTuner {
    private RemoteCommandProcessor mRemoteCommandProcessor;

    public ServerTuner(String serviceHost, Integer servicePort, String env, String serverHost){
        mRemoteCommandProcessor = new RemoteCommandProcessor(serviceHost, servicePort, env, serverHost);
    }

    public Pair<Status, String> help(){
        return mRemoteCommandProcessor.processCommand(new HelpRequest());
    }

    public Pair<Status, String> rebuildDatabase(){
        return mRemoteCommandProcessor.processCommand(new RebuildDatabaseRequest());
    }

    public Pair<Status, String> createUser(String username, String password){
        return mRemoteCommandProcessor.processCommand(new CreateUserRequest(username, password));
    }

    public void createProduct(Product product){

    }

}
