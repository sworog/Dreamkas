package ru.dreamkas.steps.general;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import ru.dreamkas.common.pageObjects.GeneralPageObject;
import ru.dreamkas.storage.DefaultStorage;

import java.util.Map;

public abstract class AbstractGeneralSteps<T extends GeneralPageObject> extends ScenarioSteps {

    abstract Map<String, Class> getPageObjectClasses();

    @Step
    public void setCurrentPageObject(String pageObjectName) throws AssertionError {
        Class pageObjectClass = getPageObjectClasses().get(pageObjectName);
        if (null == pageObjectClass) {
            throw new AssertionError(String.format("PageObject class not found by name '%s'", pageObjectName));
        }
        T currentPageObject = initPage(pageObjectClass);
        setCurrentPageObjectToStorage(currentPageObject);
    }

    protected T getCurrentPageObject() {
        return getCurrentPageObjectFromStorage();
    }

    @SuppressWarnings("unchecked")
    protected T initPage(Class pageObjectClass) {
        return (T) getPages().get(pageObjectClass);
    }

    @SuppressWarnings("unchecked")
    protected T getCurrentPageObjectFromStorage() {
        return (T) DefaultStorage.getCurrentPageObjectStorage().getCurrentPageObject();
    }

    protected void setCurrentPageObjectToStorage(T pageObject) {
        DefaultStorage.getCurrentPageObjectStorage().setCurrentPageObject(pageObject);
    }
}
