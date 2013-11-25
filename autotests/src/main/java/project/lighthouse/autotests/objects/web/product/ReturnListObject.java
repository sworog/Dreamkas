package project.lighthouse.autotests.objects.web.product;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectNode;

import java.util.Map;

public class ReturnListObject extends AbstractObjectNode {

    private String createdDateFormatted;
    private String quantity;
    private String priceFormatted;
    private String totalPriceFormatted;

    public ReturnListObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        createdDateFormatted = getElement().findElement(By.xpath(".//*[@model-attribute='createdDateFormatted']")).getText();
        quantity = getElement().findElement(By.xpath(".//*[@model-attribute='quantityElement']")).getText();
        priceFormatted = getElement().findElement(By.xpath(".//*[@model-attribute='priceFormatted']")).getText();
        totalPriceFormatted = getElement().findElement(By.xpath(".//*[@model-attribute='totalPriceFormatted']")).getText();
    }

    @Override
    public Boolean rowIsEqual(Map<String, String> row) {
        return createdDateFormatted.equals(row.get("date")) &&
                quantity.equals(row.get("quantity")) &&
                priceFormatted.equals(row.get("price")) &&
                totalPriceFormatted.equals(row.get("totalPrice"));
    }
}
