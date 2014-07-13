package project.lighthouse.autotests.api.abstractFactory;

import org.json.JSONException;
import project.lighthouse.autotests.api.HttpExecutor;
import project.lighthouse.autotests.api.HttpRequestable;
import project.lighthouse.autotests.objects.api.abstraction.AbstractObject;

import java.io.IOException;

public abstract class AbstractApiFactory {

    private HttpRequestable httpRequestable;

    public AbstractApiFactory(String email, String password) {
        httpRequestable = HttpExecutor.getHttpRequestable(email, password);
    }

    public void createObject(AbstractObject object) throws IOException, JSONException {
        httpRequestable.executePostRequest(object);
    }

    public HttpRequestable getHttpRequestable() {
        return httpRequestable;
    }
}
