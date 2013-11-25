package project.lighthouse.autotests.objects.web.log;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

import java.util.ArrayList;
import java.util.List;

public class JobLogObjectCollection extends AbstractObjectCollection {

    public JobLogObjectCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public void init(WebDriver webDriver, By findBy) {
        List<WebElement> webElementList = new Waiter(webDriver).getVisibleWebElements(findBy);
        for (WebElement element : webElementList) {
            JobLogObject jobLogObject = new JobLogObject(element, webDriver);
            add(jobLogObject);
        }
    }

    @Override
    public AbstractObject createNode(WebElement element) {
        return null;
    }

    private List<JobLogObject> getJobLogMessagesByType(String type) {
        List<JobLogObject> logMessagesByType = new ArrayList<>();
        for (AbstractObject abstractObject : this) {
            JobLogObject jobLogObject = (JobLogObject) abstractObject;
            if (jobLogObject.getType() != null) {
                if (jobLogObject.getType().equals(type)) {
                    logMessagesByType.add(jobLogObject);
                }
            }
        }
        return logMessagesByType;
    }

    public JobLogObject getLastByType(String type) {
        return getJobLogMessagesByType(type).get(0);
    }
}
