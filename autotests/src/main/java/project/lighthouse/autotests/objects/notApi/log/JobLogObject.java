package project.lighthouse.autotests.objects.notApi.log;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;

public class JobLogObject {

    String id;
    String type;
    String status;
    String title;
    String statusText;
    String product;
    String dateCreated;
    String dateStarted;
    String dateFinished;
    String duration;
    WebDriver driver;
    Waiter waiter;

    private JobLogObject(WebDriver driver) {
        this.driver = driver;
        waiter = new Waiter(driver, 1);
    }

    public JobLogObject(WebDriver driver, WebElement element) {
        this(driver);
        setProperties(element);
    }

    public String getType() {
        return this.type;
    }

    public String getProduct() {
        return this.product;
    }

    public String getStatus() {
        return this.status;
    }

    public String getTitle() {
        return this.title;
    }

    public String getStatusText() {
        return this.statusText;
    }

    private void setProperties(WebElement element) {
        this.id = element.getAttribute("id");
        this.type = element.getAttribute("type");
        this.status = element.getAttribute("status");
        this.title = element.findElement(By.xpath(".//*[@class='jobs__title']")).getText();
        this.product = null;
        if (!waiter.invisibilityOfElementLocated(By.xpath(".//*[@class='jobs__productName']"))) {
            this.product = element.findElement(By.xpath(".//*[@class='jobs__productName']")).getText();
        }
        this.statusText = element.findElement(By.xpath(".//*[@class='jobs__status']")).getText();
    }
}
