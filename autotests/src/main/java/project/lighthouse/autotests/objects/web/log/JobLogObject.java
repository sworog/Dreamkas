package project.lighthouse.autotests.objects.web.log;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;

public class JobLogObject extends AbstractObject {

    private String id;
    private String type;
    private String status;
    private String title;
    private String statusText;
    private String product;

    public JobLogObject(WebElement element, WebDriver webDriver) {
        super(element, webDriver);
    }

    public String getType() {
        return type;
    }

    public String getStatus() {
        return status;
    }

    public String getTitle() {
        return title;
    }

    public String getProduct() {
        return product;
    }

    public String getStatusText() {
        return statusText;
    }

    @Override
    public void setProperties() {
        id = getElement().getAttribute("id");
        type = getElement().getAttribute("type");
        status = getElement().getAttribute("status");
        title = setProperty(By.xpath(".//*[@class='log__title']"));
        product = setProperty(By.xpath(".//*[@class='log__productName']"));
        statusText = setProperty(By.xpath(".//*[@class='log__status']"));
    }
}
