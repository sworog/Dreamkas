package project.lighthouse.autotests.pages.commercialManager.store;


import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.UrlHelper;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.common.CommonView;
import project.lighthouse.autotests.elements.Input;

@DefaultUrl("/stores/create")
public class StoreCreatePage extends CommonPageObject {

    CommonView commonView = new CommonView(getDriver());

    public StoreCreatePage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("number", new Input(this, "number"));
        items.put("address", new Input(this, "address"));
        items.put("contacts", new Input(this, "contacts"));
    }

    public WebElement createButton() {
        return findVisibleElement(By.xpath("//*[contains(@class, 'button_color_blue') and contains(., 'Добавить')]"));
    }

    public WebElement saveButton() {
        return findVisibleElement(By.xpath("//*[contains(@class, 'button_color_blue') and contains(., 'Сохранить')]"));
    }
}
