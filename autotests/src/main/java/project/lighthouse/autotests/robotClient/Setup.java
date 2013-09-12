
package project.lighthouse.autotests.robotClient;

import javax.xml.bind.annotation.XmlAccessType;
import javax.xml.bind.annotation.XmlAccessorType;
import javax.xml.bind.annotation.XmlType;


/**
 * <p>Java class for setup complex type.
 * <p/>
 * <p>The following schema fragment specifies the expected content contained within this class.
 * <p/>
 * <pre>
 * &lt;complexType name="setup">
 *   &lt;complexContent>
 *     &lt;restriction base="{http://www.w3.org/2001/XMLSchema}anyType">
 *       &lt;sequence>
 *         &lt;element name="cashIp" type="{http://www.w3.org/2001/XMLSchema}string" minOccurs="0"/>
 *         &lt;element name="shopNumber" type="{http://www.w3.org/2001/XMLSchema}string" minOccurs="0"/>
 *         &lt;element name="cashNumber" type="{http://www.w3.org/2001/XMLSchema}string" minOccurs="0"/>
 *       &lt;/sequence>
 *     &lt;/restriction>
 *   &lt;/complexContent>
 * &lt;/complexType>
 * </pre>
 */
@XmlAccessorType(XmlAccessType.FIELD)
@XmlType(name = "setup", propOrder = {
        "cashIp",
        "shopNumber",
        "cashNumber"
})
public class Setup {

    protected String cashIp;
    protected String shopNumber;
    protected String cashNumber;

    /**
     * Gets the value of the cashIp property.
     *
     * @return possible object is
     *         {@link String }
     */
    public String getCashIp() {
        return cashIp;
    }

    /**
     * Sets the value of the cashIp property.
     *
     * @param value allowed object is
     *              {@link String }
     */
    public void setCashIp(String value) {
        this.cashIp = value;
    }

    /**
     * Gets the value of the shopNumber property.
     *
     * @return possible object is
     *         {@link String }
     */
    public String getShopNumber() {
        return shopNumber;
    }

    /**
     * Sets the value of the shopNumber property.
     *
     * @param value allowed object is
     *              {@link String }
     */
    public void setShopNumber(String value) {
        this.shopNumber = value;
    }

    /**
     * Gets the value of the cashNumber property.
     *
     * @return possible object is
     *         {@link String }
     */
    public String getCashNumber() {
        return cashNumber;
    }

    /**
     * Sets the value of the cashNumber property.
     *
     * @param value allowed object is
     *              {@link String }
     */
    public void setCashNumber(String value) {
        this.cashNumber = value;
    }

}
