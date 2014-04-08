package project.lighthouse.autotests.pages.departmentManager.writeOff;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.preLoader.PreLoader;
import project.lighthouse.autotests.objects.web.search.WriteOffSearchObjectCollection;

public class WriteOffSearchPage extends CommonPageObject {

    public WriteOffSearchPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("number", new Input(this, "number"));
    }

    public WriteOffSearchObjectCollection getWriteOffSearchObjectCollection() {
        return new WriteOffSearchObjectCollection(getDriver(), By.xpath("//*[@class='writeOffList__item']"));
    }

    public void searchButtonClick() {
        new ButtonFacade(this, "Найти").click();
        new PreLoader(getDriver()).await();
    }
}
