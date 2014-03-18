package project.lighthouse.autotests.pages.departmentManager.order.pageElements;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Autocomplete;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.Input;
import project.lighthouse.autotests.elements.NonType;
import project.lighthouse.autotests.elements.preLoader.PreLoader;

public class ProductAdditionForm extends CommonPageObject {

    public ProductAdditionForm(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("name", new Autocomplete(this, "name", "Наименование"));
        put("quantity", new Input(this, "quantity", "Количество, шт"));
        put("retailPrice", new NonType(this, "retailPrice", "Цена, руб"));
        put("totalSum", new NonType(this, "totalSum", "Сумма, руб"));
        put("inventory", new NonType(this, "inventory", "Остаток, шт"));
    }

    public void addButtonClick() {
        new ButtonFacade(this, "Добавить в заказ").click();
        new PreLoader(getDriver()).await();
    }
}
