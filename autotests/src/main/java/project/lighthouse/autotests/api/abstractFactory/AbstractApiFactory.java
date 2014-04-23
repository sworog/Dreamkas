package project.lighthouse.autotests.api.abstractFactory;

import org.json.JSONException;
import project.lighthouse.autotests.api.HttpExecutor;
import project.lighthouse.autotests.objects.api.abstraction.AbstractObject;

import java.io.IOException;

public abstract class AbstractApiFactory {

    private HttpExecutor httpExecutor;

    public AbstractApiFactory(String userName, String password) {
        httpExecutor = new HttpExecutor(userName, password);
    }

    public void createObject(AbstractObject object ) throws IOException, JSONException {
        httpExecutor.executePostRequest(object);
    }
}
