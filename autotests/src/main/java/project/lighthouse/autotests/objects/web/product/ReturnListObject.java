package project.lighthouse.autotests.objects.web.product;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectNode;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

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
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("date", createdDateFormatted, row.get("date"))
                .compare("quantity", quantity, row.get("quantity"))
                .compare("price", priceFormatted, row.get("price"))
                .compare("totalPrice", totalPriceFormatted, row.get("totalPrice"));
    }
}
