package project.lighthouse.autotests.objects.notApi.search;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.notApi.abstractObjects.AbstractSearchObjectNode;

import java.util.Map;

public class WriteOffSearchObject extends AbstractSearchObjectNode {

    private String number;
    private String date;

    public WriteOffSearchObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        number = getElement().findElement(By.xpath(".//*[@name='number']")).getText();
        date = getElement().findElement(By.xpath(".//*[@name='date']")).getText();
    }

    @Override
    public String getObjectLocator() {
        return number;
    }

    @Override
    public Boolean rowIsEqual(Map<String, String> row) {
        return number.equals(row.get("number")) &&
                date.equals(row.get("date"));
    }
}
