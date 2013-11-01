package project.lighthouse.autotests.objects.notApi.product;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.notApi.abstractObjects.AbstractObjectNode;

import java.util.Map;

public class WriteOffListObject extends AbstractObjectNode {

    private String acceptanceDateFormatted;
    private String quantity;
    private String priceFormatted;
    private String totalPriceFormatted;

    private String number;

    public WriteOffListObject(WebElement element) {
        super(element);
    }

    public void setProperties() {
        acceptanceDateFormatted = getElement().findElement(By.xpath(".//*[@model_attr='createdDateFormatted']")).getText();
        quantity = getElement().findElement(By.xpath(".//*[@model_attr='quantity']")).getText();
        priceFormatted = getElement().findElement(By.xpath(".//*[@model_attr='priceFormatted']")).getText();
        totalPriceFormatted = getElement().findElement(By.xpath(".//*[@model_attr='totalPriceFormatted']")).getText();
        number = getElement().getAttribute("writeoff-number");
    }

    public Boolean rowIsEqual(Map<String, String> row) {
        return acceptanceDateFormatted.equals(row.get("createdDateFormatted")) &&
                quantity.equals(row.get("quantity")) &&
                priceFormatted.equals(row.get("priceFormatted")) &&
                totalPriceFormatted.equals(row.get("totalPriceFormatted"));
    }

    @Override
    public String getObjectLocator() {
        return number;
    }
}
