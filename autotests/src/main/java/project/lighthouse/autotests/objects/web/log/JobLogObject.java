package project.lighthouse.autotests.objects.web.log;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectNode;

public class JobLogObject extends AbstractObjectNode {

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
        Waiter waiter = new Waiter(getWebDriver(), 0);
        id = getElement().getAttribute("id");
        type = getElement().getAttribute("type");
        status = getElement().getAttribute("status");
        if (!waiter.invisibilityOfElementLocated(By.xpath(".//*[@class='log__title']"))) {
            title = getElement().findElement(By.xpath(".//*[@class='log__title']")).getText();
        }
        product = null;
        if (!waiter.invisibilityOfElementLocated(By.xpath(".//*[@class='log__productName']"))) {
            product = getElement().findElement(By.xpath(".//*[@class='log__productName']")).getText();
        }
        statusText = getElement().findElement(By.xpath(".//*[@class='log__status']")).getText();
    }
}
