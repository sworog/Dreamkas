package project.lighthouse.autotests.api.abstractFactory;

import project.lighthouse.autotests.api.HttpExecutor;

public abstract class AbstractFactory {

    private HttpExecutor httpExecutor;

    public AbstractFactory(String userName, String password) {
        httpExecutor = new HttpExecutor(userName, password);
    }

    public HttpExecutor getHttpExecutor() {
        return httpExecutor;
    }
}
