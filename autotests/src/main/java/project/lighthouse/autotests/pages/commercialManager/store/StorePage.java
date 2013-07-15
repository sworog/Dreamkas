package project.lighthouse.autotests.pages.commercialManager.store;


import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.common.CommonView;
import project.lighthouse.autotests.elements.Input;

public class StorePage extends CommonPageObject {

    CommonView commonView = new CommonView(getDriver(), "", "");

    public StorePage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("name", new Input(this, "name"));
        //To change body of implemented methods use File | Settings | File Templates.
    }

    public void button() {
        String xpath = "";
        findVisibleElement(By.xpath(xpath)).click();
    }

    public void itemCheck(String storeNumber) {
        commonView.itemCheck(storeNumber);
    }

}
