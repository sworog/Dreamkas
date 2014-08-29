package project.lighthouse.autotests.pages.deprecated.departmentManager.writeOff;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.items.NonType;

@DefaultUrl("/writeOffs")
public class WriteOffListPage extends CommonPageObject {

    public WriteOffListPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("writeOff list page date", new NonType(this, "date"));
        put("writeOff list page number", new NonType(this, "number"));
        put("writeOff list page sumTotal", new NonType(this, "sumTotal"));
    }
}
