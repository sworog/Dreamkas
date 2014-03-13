package project.lighthouse.autotests.pages.departmentManager.order.pageElements;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Autocomplete;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.Input;
import project.lighthouse.autotests.elements.NonType;

public class ProductAdditionForm extends CommonPageObject {

    public ProductAdditionForm(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("name", new Autocomplete(this, "name", "Наименование"));
        put("quantity", new Input(this, "quantity", "Количество, шт"));
        put("price", new NonType(this, "retailPrice", "Цена, руб"));
        put("sum", new NonType(this, "sum", "Сумма, руб"));
        put("inventory", new NonType(this, "inventory", "Остаток, шт"));
    }

    public void addButtonClick() {
        new ButtonFacade(this, "Добавить в заказ").click();
    }
}
