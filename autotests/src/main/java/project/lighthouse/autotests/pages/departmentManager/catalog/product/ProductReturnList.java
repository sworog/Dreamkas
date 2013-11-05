package project.lighthouse.autotests.pages.departmentManager.catalog.product;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.web.product.ReturnListObjectCollection;

public class ProductReturnList extends CommonPageObject {

    public ProductReturnList(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public ReturnListObjectCollection getReturnListObjectCollection() {
        return new ReturnListObjectCollection(getDriver(), By.xpath("return"));
    }
}
