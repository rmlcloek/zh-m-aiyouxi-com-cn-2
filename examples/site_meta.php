<?php
/**
 * 站点元信息配置与描述生成工具
 * 用于统一管理网站基本资料和生成简洁的介绍文本
 */

class SiteMeta
{
    private array $metaData;

    public function __construct(array $config = [])
    {
        // 默认站点信息
        $defaults = [
            'site_name'        => '爱游戏',
            'domain'           => 'https://zh-m-aiyouxi.com.cn',
            'keywords'         => ['爱游戏', '游戏资讯', '玩家社区'],
            'description'      => '爱游戏是一个专注于游戏玩家体验的综合性平台。',
            'language'         => 'zh-CN',
            'version'          => '1.0.0',
            'author'           => '爱游戏团队',
            'copyright'        => '© 2025 爱游戏',
            'short_intro'      => '发现游戏乐趣，尽在爱游戏',
        ];

        // 合并自定义配置
        $this->metaData = array_merge($defaults, $config);
    }

    /**
     * 获取原始元数据
     */
    public function getMeta(string $key = null): mixed
    {
        if ($key === null) {
            return $this->metaData;
        }
        return $this->metaData[$key] ?? null;
    }

    /**
     * 生成站点简短描述文本，用于 SEO 或分享预览
     * @param int $maxLength 最大字符数（中文按一个字符算）
     * @return string
     */
    public function generateShortDescription(int $maxLength = 80): string
    {
        $parts = [
            $this->metaData['site_name'],
            '——',
            $this->metaData['short_intro'],
        ];

        $base = implode(' ', $parts);
        $baseLen = mb_strlen($base, 'UTF-8');

        if ($baseLen <= $maxLength) {
            return $base;
        }

        // 如果超长则截断并加省略号
        return mb_substr($base, 0, $maxLength - 3, 'UTF-8') . '...';
    }

    /**
     * 生成包含关键词的 HTML 元标签字符串（不转义，假设已安全）
     * 注意：实际输出时建议使用 htmlspecialchars 转义
     */
    public function renderMetaTags(): string
    {
        $name = htmlspecialchars($this->metaData['site_name'], ENT_QUOTES, 'UTF-8');
        $desc = htmlspecialchars($this->metaData['description'], ENT_QUOTES, 'UTF-8');
        $kw   = htmlspecialchars(implode(', ', $this->metaData['keywords']), ENT_QUOTES, 'UTF-8');

        return "<meta name=\"description\" content=\"{$desc}\">\n"
             . "<meta name=\"keywords\" content=\"{$kw}\">\n"
             . "<meta name=\"author\" content=\"{$name}\">\n";
    }

    /**
     * 输出简单文本描述（纯文本，无 HTML）
     */
    public function toPlainText(): string
    {
        return sprintf(
            "%s | %s\n关键词：%s\n简介：%s",
            $this->metaData['site_name'],
            $this->metaData['domain'],
            implode('、', $this->metaData['keywords']),
            $this->generateShortDescription(100)
        );
    }
}

// ---------- 使用示例 ----------

// 初始化站点元信息（可直接修改或读取配置）
$site = new SiteMeta([
    'site_name'   => '爱游戏',
    'domain'      => 'https://zh-m-aiyouxi.com.cn',
    'keywords'    => ['爱游戏', '手游推荐', '游戏评测'],
    'description' => '爱游戏提供最新游戏资讯、深度评测和玩家交流社区。',
    'short_intro' => '你的游戏好伙伴，爱游戏与你同行。',
]);

// 输出纯文本描述（可用于调试或页面嵌入）
echo $site->toPlainText() . "\n\n";

// 输出简短描述（适合 meta description 或分享卡片）
echo "简短描述：" . $site->generateShortDescription(50) . "\n\n";

// 输出 HTML meta 标签（已做转义）
echo $site->renderMetaTags();