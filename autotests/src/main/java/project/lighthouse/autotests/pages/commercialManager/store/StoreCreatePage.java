package project.lighthouse.autotests.pages.commercialManager.store;


import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Input;

@DefaultUrl("/stores/create")
public class StoreCreatePage extends CommonPageObject {

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
        return findVisibleElement(
                By.xpath("//span[@class='button button_color_blue' and contains(text(), 'Добавить')]/input")
        );
    }

    public WebElement saveButton() {
        return findVisibleElement(
                By.xpath("//span[@class='button button_color_blue' and contains(text(), 'Сохранить')]/input")
        );
    }
}
