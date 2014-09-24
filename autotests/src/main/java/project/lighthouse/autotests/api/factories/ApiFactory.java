package project.lighthouse.autotests.api.factories;

import org.json.JSONException;
import project.lighthouse.autotests.api.http.HttpExecutor;
import project.lighthouse.autotests.api.http.HttpRequestable;
import project.lighthouse.autotests.api.objects.abstraction.AbstractObject;

import java.io.IOException;

public class ApiFactory {

    private HttpRequestable httpRequestable;

    public ApiFactory(String email, String password) {
        httpRequestable = HttpExecutor.getHttpRequestable(email, password);
    }

    public AbstractObject createObject(AbstractObject object) throws IOException, JSONException {
        httpRequestable.executePostRequest(object);
        return object;
    }
}
