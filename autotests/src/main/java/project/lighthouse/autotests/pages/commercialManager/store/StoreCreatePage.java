package project.lighthouse.autotests.pages.commercialManager.store;


import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.pages.WebElementFacade;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import project.lighthouse.autotests.UrlHelper;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.common.CommonView;
import project.lighthouse.autotests.elements.Input;

@DefaultUrl("/stores/create")
public class StoreCreatePage extends CommonPageObject {

    CommonView commonView = new CommonView(getDriver());

    By createButtonBy = By.xpath("//span[@class='button button_color_blue' and contains(text(), 'Добавить')]/input");
    By saveButtonBy = By.xpath("//span[@class='button button_color_blue' and contains(text(), 'Сохранить')]/input");

    public StoreCreatePage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("number", new Input(this, "number"));
        items.put("address", new Input(this, "address"));
        items.put("contacts", new Input(this, "contacts"));
    }

    public void clickCreateButton() {
        find(createButtonBy).click();
    }

    public void clickSaveButton() {
        find(saveButtonBy).click();
    }
}
