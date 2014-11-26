package ru.dreamkas.pos;

import android.util.Pair;

import org.apache.http.client.HttpClient;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

import ru.dreamkas.pos.controller.DreamkasRestClient_;
import ru.dreamkas.pos.controller.requests.AuthRequest;
import ru.dreamkas.pos.controller.requests.AuthRequest_;
import ru.dreamkas.pos.controller.requests.CreateGroupRequest;
import ru.dreamkas.pos.controller.requests.CreateProductRequest;
import ru.dreamkas.pos.controller.requests.CreateStoreRequest;
import ru.dreamkas.pos.controller.requests.GetStoresRequest;
import ru.dreamkas.pos.model.api.AuthObject;
import ru.dreamkas.pos.model.api.GroupApiObject;
import ru.dreamkas.pos.model.api.NamedObject;
import ru.dreamkas.pos.model.api.ProductApiObject;
import ru.dreamkas.pos.model.api.StoreApiObject;
import ru.dreamkas.pos.model.api.Token;
import ru.dreamkas.pos.model.api.collections.NamedObjects;
import ru.dreamkas.pos.remoteCommand.CreateApiClientRequest;
import ru.dreamkas.pos.remoteCommand.CreateDatabaseRequest;
import ru.dreamkas.pos.remoteCommand.CreateUserRequest;
import ru.dreamkas.pos.remoteCommand.DropDatabaseRequest;
import ru.dreamkas.pos.remoteCommand.HelpRequest;
import ru.dreamkas.pos.remoteCommand.RemoteCommandProcessor;
import ru.dreamkas.pos.remoteCommand.Request;
import ru.dreamkas.pos.remoteCommand.Status;


public class ServerTuner {
    private RemoteCommandProcessor mRemoteCommandProcessor;
    private String SECRET = "secret";
    private String WEBFRONT_API_CLIENT_NAME = "webfront";

    private Map<String, String> mUsers = new HashMap<String, String>();
    private Map<String, String> mProductGroups = new HashMap<String, String>();
    private DreamkasRestClient_ mRestClient;

    public ServerTuner(String serviceHost, Integer servicePort, String env, String serverHost){
        mRemoteCommandProcessor = new RemoteCommandProcessor(serviceHost, servicePort, env, serverHost);
    }

    private String getApiClientId(String name){
        return name + "_" + name;
    }

    public Pair<Status, String> help(){
        return mRemoteCommandProcessor.processCommand(new HelpRequest());
    }

    public Pair<Status, String> rebuildDatabase(){
        ArrayList<Request> requests = new ArrayList<Request>();

        requests.add(new DropDatabaseRequest());
        requests.add(new CreateDatabaseRequest());
        requests.add(new CreateApiClientRequest(SECRET, "autotests"));
        requests.add(new CreateApiClientRequest(SECRET, "android"));
        requests.add(new CreateApiClientRequest(SECRET, WEBFRONT_API_CLIENT_NAME));

        String log = "";
        Status lastStatus = Status.UNKNOWN;
        for(Request request : requests){
            Pair<Status, String> result = mRemoteCommandProcessor.processCommand(request);
            log += result.second;
            lastStatus = result.first;
            if(lastStatus != Status.SUCCESS){
                break;
            }
        }

        return new Pair<Status, String>(lastStatus, log);
    }

    public Pair<Status, String> createUser(String username, String password){
        Pair<Status, String> result = mRemoteCommandProcessor.processCommand(new CreateUserRequest(username, password));
        mUsers.put(username, password);
        return result;
    }

    public void auth(String username) throws Exception {
        String pass = mUsers.get(username);
        if(pass == null){
            pass = "lighthouse";
        }

        AuthObject ao = new AuthObject(getApiClientId(WEBFRONT_API_CLIENT_NAME), username, pass, SECRET);
        AuthRequest_ authRequest = AuthRequest_.getInstance_(null);
        authRequest.setCredentials(ao);
        Token authResponse = authRequest.loadDataFromNetwork();

        if(authResponse != null){
            mRestClient = new DreamkasRestClient_();
            mRestClient.setHeader("Authorization", "Bearer " + authResponse.getAccess_token());
        }
    }

    public boolean createStoreByUserWithEmail(String name, String address, String email) throws Exception {
        if(mRestClient == null){
            auth(email);
        }

        CreateStoreRequest createStoreRequest = new CreateStoreRequest();
        createStoreRequest.init(mRestClient);
        createStoreRequest.setStore(new StoreApiObject(address, name));
        NamedObject result = createStoreRequest.loadDataFromNetwork();
        return result != null;
    }

    public boolean createGroupThroughPostByUserWithEmail(String groupName, String email) throws Exception {
        if(mRestClient == null){
            auth(email);
        }

        CreateGroupRequest createGroupRequest = new CreateGroupRequest();
        createGroupRequest.init(mRestClient);
        createGroupRequest.setGroup(new GroupApiObject(groupName));
        NamedObject result = createGroupRequest.loadDataFromNetwork();

        if(result != null){
            mProductGroups.put(groupName, result.getId());
        }
        return result != null;
    }

    public boolean createProduct(String name,
                                 String units,
                                 String barcode,
                                 String vat,
                                 Double purchasePrice,
                                 Double sellingPrice,
                                 String groupName, String email) throws Exception {
        if(mRestClient == null){
            auth(email);
        }

        CreateProductRequest createProductRequest = new CreateProductRequest();
        createProductRequest.init(mRestClient);
        createProductRequest.setProduct(new ProductApiObject(name, units, barcode, vat, sellingPrice, purchasePrice, mProductGroups.get(groupName)));
        NamedObject result = createProductRequest.loadDataFromNetwork();

        return result != null;
    }


}
