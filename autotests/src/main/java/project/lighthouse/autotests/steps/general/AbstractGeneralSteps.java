package project.lighthouse.autotests.steps.general;

import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.common.GeneralPageObject;
import project.lighthouse.autotests.storage.Storage;

import java.util.Map;

public abstract class AbstractGeneralSteps<T extends GeneralPageObject> extends ScenarioSteps {

    private T currentPageObject;

    abstract Map<String, Class> getPageObjectClasses();

    public void setCurrentPageObject(String pageObjectName) {
        Class pageObjectClass = getPageObjectClasses().get(pageObjectName);
        currentPageObject = initPage(pageObjectClass);
        setCurrentPageObjectToStorage(currentPageObject);
    }

    protected T getCurrentPageObject() {
        if (null == currentPageObject) {
            currentPageObject = getCurrentPageObjectFromStorage();
        }
        return currentPageObject;
    }

    @SuppressWarnings("unchecked")
    protected T initPage(Class pageObjectClass) {
        return (T) getPages().get(pageObjectClass);
    }

    @SuppressWarnings("unchecked")
    protected T getCurrentPageObjectFromStorage() {
        return (T) Storage.getCurrentPageObjectStorage().getCurrentPageObject();
    }

    protected void setCurrentPageObjectToStorage(T pageObject) {
        Storage.getCurrentPageObjectStorage().setCurrentPageObject(pageObject);
    }
}
