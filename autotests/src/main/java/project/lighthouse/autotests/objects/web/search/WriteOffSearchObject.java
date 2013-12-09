package project.lighthouse.autotests.objects.web.search;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractSearchObjectNode;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

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
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("number", number, row.get("number"))
                .compare("date", date, row.get("date"));
    }
}
